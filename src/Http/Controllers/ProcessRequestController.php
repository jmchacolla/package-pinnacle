<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers;

use Exception;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackagePinnacle\Http\Resources\PinnaclePaymentRecord as PinnaclePaymentRecordResources;
use ProcessMaker\Package\PackagePinnacle\Http\Resources\ProcessRequests as ProcessRequestsResources;
use ProcessMaker\Package\PackagePinnacle\Http\Resources\PinnacleBroadcast as PinnacleBroadcastResources;
use ProcessMaker\Package\PackagePinnacle\Classes\DateTime;
use ProcessMaker\Package\PackagePinnacle\Models\PinnaclePaymentRecord;
use ProcessMaker\Package\PackagePinnacle\Models\PinnacleExcursionSlip;
use ProcessMaker\Package\PackagePinnacle\Models\PinnacleBroadcast;
use ProcessMaker\Package\PackagePinnacle\Models\ProcessRequest;
use ProcessMaker\Package\PackagePinnacle\Models\Student;
use ProcessMaker\Package\PackagePinnacle\Models\Staff;
use ProcessMaker\Models\EnvironmentVariable;
use ProcessMaker\Package\PackagePinnacle\Models\User;
use Illuminate\Http\Request;
use RBAC;
use DB;
use URL;

class ProcessRequestController extends Controller
{
    /**
     * Return a list of Cases of Pinnacle College - Excursion Slip
     * @return view
     */
    public function showProcessRequestList()
    {
        ////---- setting process id from the config table if exists
        $processId     = EnvironmentVariable::whereName('PINN_PROCESS_EXCURSION_SLIP_ID')->first()->value;

        $processRequest = ProcessRequest::getAllRequestByProcessId($processId);

        $newProcessRequest = [];
        foreach($processRequest as $key => $case){
            $case['notification_sent'] = PinnaclePaymentRecord::getAmountNotificationsSentByRequest($case['id']);
            $case['responses_done']    = PinnaclePaymentRecord::getAmountPermmisionsResponsesByRequest($case['id']);
            array_push($newProcessRequest, $case);
        }

        $countRequest = ProcessRequest::getAmontRequestByStatus($processId, 'SENT');
        $slipResponse = PinnaclePaymentRecord::getAllPermissionsResponses();

        return view('package-pinnacle::processRequest',
            [
                'processRequest' => $newProcessRequest,
                'countRequest'     => $countRequest,
                'slipResponse'     => $slipResponse
            ]);
    }

    /**
     * Show Request
     * @param String $caseId
     *
     * @return view
     */
    public function showProcessRequest(String $caseId)
    {
        try {
            $processRequest        = ProcessRequest::getRequestById($caseId);
            $files                 = ProcessRequest::getRequestFiles($caseId);
            $pinnaclePaymentRecord = PinnaclePaymentRecord::getAllPaymentRecordAndStudentByRequest($caseId);
            $caseResponse          = PinnaclePaymentRecord::getAmountOfResponsesByRequest($caseId);

            $broadcastList   = PinnacleBroadcast::getBroadcastByRequestId($caseId);
            $allStudentsList = Student::getStudents();
            $allStaffList    = Staff::getAllStaff();

            ////---- setting process id from the config table if exists
            $scriptSenEmailAttendanceId = EnvironmentVariable::whereName('PINN_ID_SCRIPT_SEND_ATTENDANCE_EMAIL')->first()->value;
            $scriptSentResentReopen     = EnvironmentVariable::whereName('PINN_ID_SCRIPT_SEND_RESENT_REOPEN')->first()->value;
            $apiToken                   = EnvironmentVariable::whereName('PINN_API_TOKEN')->first()->value;
            $publishableApiKey          = EnvironmentVariable::whereName('PAYWAY_PUBLISHABLE_API_KEY')->first()->value;

            $studentListToRemove = $pinnaclePaymentRecord->map(function ($item, $key) {
                return ['id' => $item['user_id'], 'text' => $item['firstName'] . ' ' . $item['lastName']];
            });

            $varsJs = [
                'appData'                    => $processRequest['data'],
                'broadcastList'              => $broadcastList,
                'allStudentsList'            => $allStudentsList,
                'allStaffList'               => $allStaffList,
                'scriptSenEmailAttendanceId' => $scriptSenEmailAttendanceId,
                'scriptSentResentReopen'     => $scriptSentResentReopen,
                'apiToken'                   => $apiToken,
                'files'                      => $files,
                'pinnaclePaymentRecord'      => $pinnaclePaymentRecord->toArray(),
                'studentsAmount'             => $pinnaclePaymentRecord->count(),
                'caseResponse'               => $caseResponse,
                'caseId'                     => $caseId,
                'studentListToRemove'        => $studentListToRemove->toArray(),
                'studentList'                => $pinnaclePaymentRecord->toArray(),
                'publishableApiKey'          => $publishableApiKey
            ];

            return  view('package-pinnacle::processRequestDetail', compact('varsJs'));

        } catch (Exception $exception) {
            $varsJs = ['lockMessage' => $exception->getMessage()];
            return view('package-pinnacle::lockScreen', compact('varsJs'));
        }
    }

    /**
     * Redirect to view form Review Attended
     * @param String $caseId
     *
     * @return view
     */
    public function showReviewAttended(String $caseId)
    {
        $rocessRequest = ProcessRequest::findOrFail($caseId);
        $excursionSlip    = PinnacleExcursionSlip::where('case_id', $caseId)->get()->toArray();
        $pinnaclePaymentRecord = PinnaclePaymentRecord::select(
            'pinnacle_payment_record.*',
            'uvProcessMakerStudents.firstName',
            'uvProcessMakerStudents.lastName',
            'uvProcessMakerStudents.gradegroup',
            'uvProcessMakerStudents.picture'
            )
        ->where('case_id', $caseId)
        ->join('uvProcessMakerStudents', 'pinnacle_payment_record.user_id', '=', 'uvProcessMakerStudents.studentNo')
        ->where('uvProcessMakerStudents.status', 'ACT')
        ->orderBy('uvProcessMakerStudents.lastName', 'ASC')
        ->get()
        ->toArray();


        $studentsAmount = PinnaclePaymentRecord::where('case_id', $caseId)->count();
        $caseResponse   = PinnaclePaymentRecord::where('case_id', $caseId)
        ->where('response_status', '!=', 'PENDING')->count();

        $studentList = PinnaclePaymentRecord::select('uvProcessMakerStudents.*')
        ->join('uvProcessMakerStudents', 'pinnacle_payment_record.user_id', 'uvProcessMakerStudents.studentNo')
        ->where('case_id', $caseId)
        ->where('uvProcessMakerStudents.status', 'ACT')
        ->get()->toArray();
        // $broadcastList = PinnacleBroadcast::getBroadcastByRequestId($caseId);

        $appData          = $processRequest['data'];

        return  view('package-pinnacle::reviewAttended',
            [
                'pmProcessRequest' => $processRequest,
                'excursionSlip'    => $excursionSlip,
                'pinnaclePaymentRecord' => $pinnaclePaymentRecord,
                'appData'          => $appData,
                'studentsAmount'   => $studentsAmount,
                'caseResponse'     => $caseResponse,
                'caseId'           => $caseId,
                'studentList'      => $studentList
            ]
            // , compact('varsJs')
        );
    }
}
