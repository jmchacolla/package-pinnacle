<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers\Api;

use Exception;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackagePinnacle\Http\Resources\ProcessRequests as ProcessRequestsResources;
use ProcessMaker\Package\PackagePinnacle\Classes\DateTime;
use ProcessMaker\Package\PackagePinnacle\Models\PinnaclePaymentRecord;
use ProcessMaker\Package\PackagePinnacle\Models\PinnacleExcursionSlip;
use ProcessMaker\Package\PackagePinnacle\Models\PinnacleBroadcast;
use ProcessMaker\Package\PackagePinnacle\Models\ProcessRequest;
use ProcessMaker\Package\PackagePinnacle\Models\Student;
use ProcessMaker\Package\PackagePinnacle\Models\Staff;
use DB;
use ProcessMaker\Package\PackagePinnacle\Models\User;
use RBAC;
use Illuminate\Http\Request;
use URL;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request AS GRequest;

class ProcessRequestController extends Controller
{
    /**
    * Duplicate request
    * @param mixed $requestId
    *
    * @return Response
    */
    public function cloneRequest($requestId)
    {
        try {
            $processRequest = ProcessRequest::cloneRequest($requestId);
            return response()->json($processRequest);
        } catch (Exception $exception) {
            return response()->json(['data' => [], 'error' => true, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Cancel Request, delete Slip, Payment Record, Broadcast of Request
     * @param int $caseId
     *
     * @return array
     */
    public function deleteSLip(int $caseId)
    {
        try {
            $pinnaclePaymentRecord = PinnaclePaymentRecord::deletePaymentRecordBySlipId($caseId);
            $broadcastList         = PinnacleBroadcast::deleteBroadcastByRequestId($caseId);
            $pinnacleExcursionSlip = PinnacleExcursionSlip::deleteExcursionSlipByRequestId($caseId);
            $processRequest      = ProcessRequest::updateProcessRequest(['status' => 'CANCELED'], $caseId);

            return ['status' => true];

        } catch (Exception $exception) {
            throw new Exception('Error function deleteSLip: ' . $exception->getMessage());
        }
    }

    /**
    *
    * @param Request $request
    *
    * @return array
    */
    public function getAllRequestByStatus(Request $request)
    {
        try {

            ////---- setting process id from the config table if exists
            $processId = EnvironmentVariable::whereName('PINN_PROCESS_EXCURSION_SLIP_ID')->first()->value;
            $requestData = $request->all();


            $processRequest = ProcessRequest::where('process_id', $processId)
            ->where('status', 'ACTIVE')
            ->get()
            ->toArray();

            return new ProcessRequestsResources($processRequest);
        } catch (Exception $exception) {
            throw new Exception('Error function : ' . $exception->getMessage());
        }
    }

    /**
    * Get Request's data
    * @param string $requestId
    *
    * @return Response
    */
    public function getRequestData($requestId)
    {
        $caseData = ProcessRequest::getRequestData($requestId);
        $response = new ProcessRequestsResources($caseData['data']);
        return $response->getData();
    }

    /**
     * Return all files uploaded on request form by request id
     * @param int $id
     *
     * @return mixed
     */
    public function getRequestFiles(int $id) {
        $request = ProcessRequest::getRequestFiles($id);
        return $request;
    }

    /**
     * Update request's data by request id
     * @param Request $request
     * @param int $id
     *
     * @return array
     */
    public function updateProcessRequestData(Request $request, int $id)
    {
        try {

            $processRequest = ProcessRequest::updateProcessRequestData($request->all()['data'], $id);
            return new ProcessRequestsResources($processRequest->data);

        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to update Request: ' . $exception->getMessage()];
            throw new Exception('Error updateProcessrequest: ' . $exception->getMessage());
        }
    }

    /**
     * Update Request
     * @param Request $request
     * @param int $id
     *
     * @return mixed
     */
    public function updateProcessRequest(Request $request, int $id)
    {
        try {
            $response= ProcessRequest::updateProcessRequest($request->all(), $id);
            return  new ProcessRequestsResources($response);

        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to update Request: ' . $exception->getMessage()];
            throw new Exception('Error updateProcessrequest: ' . $exception->getMessage());
        }
    }


}