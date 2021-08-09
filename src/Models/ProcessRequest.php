<?php

namespace ProcessMaker\Package\PackagePinnacle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use ProcessMaker\Package\PackagePinnacle\Classes\DateTime;
use ProcessMaker\Models\ProcessRequest as PMProcessRequest;
use ProcessMaker\Models\EnvironmentVariable;
use ProcessMaker\Package\PackagePinnacle\Http\Resources\ProcessRequests as ResourcesProcessRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request AS GRequest;
use \Exception;
use \DB;

class ProcessRequest extends Model
{
    protected $table   = 'process_requests';
    protected $casts = [
        'data' => 'array',
    ];
    public $timestamps = true;

    protected $fillable = [
        'id',
        'data',
        'status'
    ];


    /** Transform the initiated_at date Attribut to format j M Y @ H:iA
     * @param mixed $value
     *
     * @return date
     */
    public function getInitiatedAtAttribute($value) {
        try {

            $user     = Auth::user();
            $dateTime = new DateTime();

            if(empty($user->timezone)) {
                $adminTZ  = User::getUserTimezone(1);
                $timeZone = $adminTZ;
            } else {
                $timeZone = $user->timezone;
            }

            $userTimeZoneDate = $dateTime->convertTimeZone($value, 'UTC', $timeZone, 'Y-m-d H:i:s');
            $date = str_replace('/', '-', $userTimeZoneDate );
            $newDate = date('j M Y @ H:iA', strtotime($date));
            return $newDate;
        } catch (Exception $exception) {
            throw new Exception('Error to get data: '. $exception->getMessage());
        }
    }

    /**
     * Return the files uploaded on the Form on request
     * @param String $id
     *
     * @return array
     */
    static function getRequestFiles(String $id)
    {
        try {
            $fileId = static::where('id', $id)
            ->first();
            $idFiles = empty($fileId->data['uploadFiles']) ? [] : json_decode($fileId->data['uploadFiles'], true);

            if (!empty($idFiles)) {
                $file = DB::table('media')
                ->whereIn('media.id', $idFiles)
                ->where('media.model_id', $id)
                ->get();
                return ['data' => $file];
            } else {
                return ['data' => []];
            }

        } catch (Exception $exception) {
            return $response['error'] = 'There are errors in the Function: getBroadcastFiles ' . $exception->getMessage();
        }
    }

    /**
     * Return the Data of Request by id
     * @param mixed $requestId
     *
     * @return array
     */
    public static function getRequestData($requestId)
    {
        $query = static::select('data')->find($requestId)->toArray();
        return $query['data'];
    }

   /**
    * Update Request's data
    * @param Array $data
    * @param int $id
    *
    * @return model
    */
    static function updateProcessRequest(Array $data, int $id)
    {
        try {
            $processRequest = static::find($id)->update($data);

            return $processRequest;
        } catch (Exception $exception) {
            throw new Exception('Error updateProcessrequest: ' . $exception->getMessage());
        }
    }

   /**
    * Get Request of process
    * @param String $processId
    *
    * @return array
    */
    static function getAllRequestByProcessId(String $processId)
    {
        return static::where('process_id', $processId)
            ->where('status', '!=', 'CANCELED')
            ->orderBy('process_requests.id', 'DESC')
            ->get();
    }

   /**
    * Get amount Resquest by data['status']
    * @param String $processId
    * @param String $status
    *
    * @return int
    */
    static function getAmontRequestByStatus(String $processId, String $status)
    {
        return static::where('process_id', $processId)->where('status', '!=', 'CANCELED')
            ->where('data->status', $status)
            ->count();
    }

   /**
    * Get a request by id
    * @param String $requestId
    *
    * @return model
    */
    static function getRequestById(String $requestId)
    {
        return static::find($requestId);
    }

   /**
    * Duplicate request
    * @param mixed $requestId
    *
    * @return array
    */
    static function cloneRequest($requestId)
    {
        ////---- API settings
        $pmToken   = EnvironmentVariable::whereName('PINN_API_TOKEN')->first()->value;
        $processId = EnvironmentVariable::whereName('PINN_PROCESS_EXCURSION_SLIP_ID')->first()->value;
        $baseUrl   = EnvironmentVariable::whereName('PM_BASE_URL')->first()->value;

        ////---- getting the request data
        $requestData = ProcessRequest::getRequestData($requestId);

        ////---- API config
        $client = new Client([
            'curl' => [CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0],
            'allow_redirects' => false,
            'cookies' => true,
            'verify' => false
        ]);
        ////---- setting url server
        $apiUrl = $baseUrl . '/api/1.0/process_events/' . $processId . '?event=startEvent';
        ////---- getting API response
        $response = $client->post($apiUrl, [
            'json' => $requestData,
            'headers' => [
                'Authorization' => 'Bearer '. $pmToken,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json'
            ]
        ]);
        if ($response->getStatusCode() === 201) {
            $responseResult = json_decode($response->getBody()->getContents(), true);
            $nextRequestId  = $responseResult['id'];
            $clonedRequestUrl = $baseUrl . 'requests/' . $nextRequestId;
            return ['data' => $clonedRequestUrl, 'error' => false, 'message' => 'The request was cloned successfully!'];
        }
        return ['data' => [], 'error' => true, 'message' => 'Error to duplicate Request.'];
    }

    /**
     * Update request's data
     * @param Array $data
     * @param String $id
     *
     * @return object
     */
    static function updateProcessRequestData(Array $data, String $id)
    {
            $processRequest = ProcessRequest::find($id);

            $requestData = $processRequest->data;
            foreach ($data as $key => $value) {
                if(gettype(json_decode($value)) != 'array') {
                    $requestData[$key] = $value;
                } else {
                    $requestData[$key] = json_decode($value, true);
                }
            }
            $processRequest->update(['data' => $requestData]);

        return $processRequest;
    }
}
