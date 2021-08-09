<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers\Api;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Package\PackagePinnacle\Models\PinnaclePaymentRecord;
use ProcessMaker\Package\PackagePinnacle\Models\ProcessRequest;
use ProcessMaker\Package\PackagePinnacle\Models\Student;
use ProcessMaker\Package\PackagePinnacle\Http\Resources\PinnaclePaymentRecord as PinnaclePaymentRecordResource;
use RBAC;
use Illuminate\Http\Request;
use URL;
use \Exception;


class PinnaclePaymentRecordController extends Controller
{

    public function store(Request $request){
        return PinnaclePaymentRecord::storePaymentRecord($request->all());
    }

    public function getPaymentsByCaseId(Request $request, int $caseId)
    {
        return PinnaclePaymentRecord::getPaymentRecordOfRequestByStatusResponse($request->all(), $caseId);
    }

    public function updateOrCreateByCaseAndUser(Request $request)
    {
        $requestData = $request->input('data');

        return PinnaclePaymentRecord::updateOrCreateByCaseAndUser($request->input('data'));
    }

    public function getStudentSlipDetail(int $id, int $caseId)
    {
        $processRequest = ProcessRequest::where('id', $caseId)->get()->toArray();
        // $student        = PinnacleUsers::select('firstname', 'lastname')->where('id',$id)->first();
        $student        = Student::select('firstName', 'lastName')->where('studentNo',$id)->first();
        $formStatus     = PinnaclePaymentRecord::where('user_id', $id)->where('case_id', $caseId)->first();

        $newForm = array(
            'transaction_id'     => null,
            'credit_card_number' => null,
            'amount_paid'        => null,
            'transaction_status' => null
        );

        return [
            'caseId'     => $caseId,
            'userId'     => $id,
            'caseData'   => $processRequest[0]['data'],
            'student'    => $student,
            'formStatus' => empty($formStatus) ? $newForm : ($formStatus)
        ];
    }
    public function updateResponse(Request $request,int $id)
    {
        $requestData = $request->input('data');
        $pinnaclePaymentRecord = new PinnaclePaymentRecord();
        $pinnaclePaymentRecord->where('id', $id)->update($requestData);
        return $pinnaclePaymentRecord;
    }

    public function update(Request $request, int $id)
    {
        try {
            $requestData = $request->input('data');
            $pinnaclePaymentRecord = new PinnaclePaymentRecord();

            $pinnaclePaymentRecord->where('id', $id)->update($requestData);
            return $pinnaclePaymentRecord;
        } catch (Exception $exception) {
            throw new Exception('Error to update Payment record: ' . $exception->getMessage());

        }
    }

    public function getStudentListBySlip(int $caseId)
    {
        try {
            $requestData = $request->all();
            $campus = empty($requestData['campus']) ? '' : $requestData['campus'];

            $studentList = static::select()
            ->join('student', 'pinnacle_payment_record.user_id', '=', 'student.studentNO')
            ->where('pinnacle_payment_record.case_id', $caseId)
            ->when(!empty($campus), function($query, $campus) {
                return $query->where('student.locationId', $campus);
            })
            ->get()
            ->toArray();
            return $studentList;
        } catch (Exception $exception) {
            throw new Exception('Error function getStudentListBySlip: ' . $exception->getMessage());
        }
    }

    public function createMassive(Request $request, int $caseId)
    {
        try {
            $requestData = $request->all();
            if (!empty($requestData['data']['studentList'])) {
                $studentList = $requestData['data']['studentList'];
                foreach ($studentList as $student) {
                    $pinnaclePaymentRecord = new PinnaclePaymentRecord();
                    $exist = $pinnaclePaymentRecord->where('user_id', $student)
                    ->where('case_id', $caseId)
                    ->get()
                    ->toArray();
                    if (empty($exist)) {
                        $pinnaclePaymentRecord->case_id = $caseId;
                        $pinnaclePaymentRecord->user_id = $student;
                        $pinnaclePaymentRecord->save();
                    }
                }
            }
            return ['status' => true];
        } catch (Exception $exception) {
            throw new Exception('Error function createMassive: ' . $exception->getMessage());
        }
    }

    public function deleteMassive(Request $request, int $caseId)
    {
        try {
            $requestData = $request->all();
            if (!empty($requestData['data']['studentList'])) {
                $studentList = $requestData['data']['studentList'];
                foreach ($studentList as $student) {
                    $pinnaclePaymentRecord = new PinnaclePaymentRecord();
                    $flight = $pinnaclePaymentRecord->where('user_id', $student)
                    ->where('case_id', $caseId)
                    ->first()
                    ->delete();
                }
            }
            return ['status' => true];
        } catch (Exception $exception) {
            throw new Exception('Error function deleteMassive: ' . $exception->getMessage());
        }
    }

    public function getPaymentRecordByStudentNo(String $studentNo, Request $request)
    {
        try {
            $requestData = $request->all();
            // return($requestData['requestId']);
            $paymentRecord = PinnaclePaymentRecord::where('user_id', $studentNo)
            ->where('case_id', $requestData['requestId'])
            ->orderBy('created_at', 'desc')
            ->first()
            ;
            // return $paymentRecord;
            // return new PinnaclePaymentRecordResource($paymentRecord);

            // throw new \Exception('Errooooooooooooor');
            return $paymentRecord;
            return ['data' => $paymentRecord];


        } catch (Exception $exception) {
            // return [$exception];
            throw new Exception('Error function getPaymentRecordByStudentNO: ' . $exception->getMessage());
        }
    }


}