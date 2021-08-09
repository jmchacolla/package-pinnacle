<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackagePinnacle\Models\ProcessRequest;
use ProcessMaker\Package\PackagePinnacle\Models\Student;
use ProcessMaker\Package\PackagePinnacle\Models\PinnacleExcursionSlip;
use ProcessMaker\Package\PackagePinnacle\Models\PinnaclePaymentRecord;
use ProcessMaker\Package\PackagePinnacle\Classes\DocumentsGenerator;
use ProcessMaker\Models\EnvironmentVariable;
use ProcessMaker\Package\PackagePinnacle\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use RBAC;
use Illuminate\Http\Request;
use URL;
use \Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use ProcessMaker\Package\PackagePinnacle\Classes\DateTime;

class ParentApprovalController extends Controller
{
    public function index(){
        return view('package-pinnacle::parentApprovalForm');
    }

    public function showParentApprovalForm(int $caseId, int $userId)
    {
        try {
            $userAdminTZ = User::getUserTimezone(1);

            $processRequest = ProcessRequest::where('id', $caseId)->get()->toArray();
            $student        = Student::select('firstName', 'lastName')->where('studentNo', $userId)->first();
            $formStatus     = PinnaclePaymentRecord::where('user_id', $userId)->where('case_id', $caseId)->first();

            $publishableApiKey = EnvironmentVariable::whereName('PAYWAY_PUBLISHABLE_API_KEY')->first()->value;

            if($formStatus['response_status'] == 'DONE'){
                throw new Exception('The form has already been completed');
            }

            if(empty($processRequest[0]['data']['status']) || $processRequest[0]['data']['status'] != 'SENT') {
                throw new Exception('The form is not yet available.');
            }

            if(!empty($processRequest[0]['data']['closeResponse'])
                && $processRequest[0]['data']['closeResponse'] == 1
                && !empty($processRequest[0]['data']['closingDate']) ){

                $closingDate       = strtotime($processRequest[0]['data']['closingDate']);
                $closingDateFormat = date('Y-m-d', $closingDate);

                $dateTime         = new DateTime();
                $currentDateUTC   = date('Y-m-d');
                $userTimeZoneDate = $dateTime->convertTimeZone($currentDateUTC, 'UTC', $userAdminTZ, 'Y-m-d');
                $date             = str_replace('/', '-', $userTimeZoneDate );
                $newDate          = date('Y-m-d', strtotime($date));

                if ($closingDateFormat < $newDate) {
                    throw new Exception('The time limit to complete the form has expired on : ' . date('m-d-Y', $closingDate) . '.' );
                }
            }

            if (!empty($processRequest[0]['data']['limitResponses'])
                && $processRequest[0]['data']['limitResponses'] == 'FIRST'
                && !empty($processRequest[0]['data']['limitFirst'])
                && !empty($processRequest[0]['data']['allowWaitList'])
                && $processRequest[0]['data']['allowWaitList'] == 'NO'
                ) {

                $pinnaclePayment = PinnaclePaymentRecord::where('case_id', $caseId)
                ->where('permission', 'YES')
                ->count();

                if ($pinnaclePayment >= $processRequest[0]['data']['limitFirst']) {
                    throw new Exception('The limit of positive responses has been met.' );
                }
            }

            if (empty($processRequest[0]) || empty($student) || empty($formStatus)) {
                throw new Exception('The page you are requesting is not available.' .$processRequest[0] . $student .$formStatus);
            }

            $newForm = array(
                'transaction_id' => null,
                'credit_card_number' => null,
                'amount_paid' => null,
                'transaction_status' => null
            );

            return view('package-pinnacle::parentApprovalForm',
                [
                    'caseId'     => $caseId,
                    'userId'     => $userId,
                    'caseData'   => $processRequest[0]['data'],
                    'student'    => $student,
                    'formStatus' => empty($formStatus) ? $newForm : $formStatus,
                    'publishableApiKey'    => empty($publishableApiKey) ? '' :  $publishableApiKey

                ]
            );
        } catch (Exception $exception) {

            $varsJs = [
                'lockMessage' => $exception->getMessage()
            ];

            return view('package-pinnacle::lockScreen', compact('varsJs'));
        }
    }

    public function printForms(Request $request, String $caseId)
    {
        try {
            $requestData     = $request->all();
            $pinnaclePayment = PinnaclePaymentRecord::getListToPrint($request->all(), $caseId);
            $processRequest  = ProcessRequest::getRequestData($caseId);

            if (count($pinnaclePayment) <= 0) {
                return redirect('pinnacle/print-blank-form/' . $caseId);
            }

            $html = '';

            foreach($pinnaclePayment as $key => $item) {
                $document = new DocumentsGenerator();
                $html    .= $document->templatePrintFomsByResponse($item, $key);
            }

            $fileName = empty($processRequest['data']['slipTitle']) ? 'ExcursionSlip_' . date('m-d-Y_his') . '.pdf'
                : htmlentities(str_replace(' ', '_', $processRequest['data']['slipTitle'])) .'_'. $requestData['status'] . '_' . date('m-d-Y_his') . '.pdf';
            $dompdf = new Dompdf();
            $dompdf->set_option('isHtml5ParserEnabled' , true);
            $dompdf->set_option('isRemoteEnabled' , true);
            $dompdf->loadHtml($html);
            $dompdf->set_paper('Letter', 'portrait');
            $dompdf->render();

            return $dompdf->stream($fileName);

        } catch (Exception $exception) {
            return $response['error'] = 'There are errors in the Function printForms:  ' . $exception->getMessage();
        }
    }

    public function printBlankForms(String $caseId)
    {
        try {
            $processRequest = ProcessRequest::getRequestData($caseId);

            $document = new DocumentsGenerator();
            $html     = $document->templateBlankForm($processRequest);
            $fileName = empty($processRequest['data']['slipTitle']) ? date('m-d-Y_his') . '.pdf' : htmlentities(str_replace(' ', '_', $processRequest['data']['slipTitle'])) . '_' . date('m-d-Y_his') . '.pdf';

            $dompdf   = new Dompdf();
            $dompdf->set_option('isHtml5ParserEnabled' , true);
            $dompdf->set_option('isRemoteEnabled' , true);
            $dompdf->loadHtml($html);
            $dompdf->set_paper('Letter', 'portrait');
            $dompdf->render();

            return $dompdf->stream($fileName);
        } catch (Exception $exception) {
            return $response['error'] = 'There are errors in the Function printBlankForms:  ' . $exception->getMessage();
        }
    }
}