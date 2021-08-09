<?php

namespace ProcessMaker\Package\PackagePinnacle\Models;

use Illuminate\Database\Eloquent\Model;
use \DB;

class PinnacleBroadcast extends Model
{
    protected $table = 'pinnacle_broadcast';
    public $timestamps = true;
    protected $casts = [
        'copy_user_group_config' => 'array',
        'files' => 'array'
    ];

    protected $fillable = [
        'broadcast_id',
        'request_id',
        'broadcast_title',
        'slip_title',
        'sent_for_status',
        'responder_id',
        'cc_notifications',
        'date_to_send',
        'time_to_send',
        'send_email',
        'send_sms',
        'copy_user_group',
        'copy_user_group_config',
        'sms_content',
        'email_content',
        'files',
        'status'
    ];

   /**
    * Get all Broadcast of Request
    * @param String $requestId
    *
    * @return Array
    */
    static function getBroadcastByRequestId(String $requestId)
    {
        try {
            $query = static::where('request_id', $requestId)
            ->get()->toArray();
            return $query;

        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to get Broadcast list getBroadcastByRequestId: ' . $exception->getMessage()];
        }
    }

   /**
    * Delete al Broadcast by Request
    * @param int|null $requestId
    *
    * @return Array
    */
    static function deleteBroadcastByRequestId(int $requestId = null)
    {
        try {
            if (empty($requestId)) {
                throw new Exception('The Request ID is required. ');
            }
            $broadcast = static::where('request_id', $requestId)->delete();
            return $broadcast;
        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to delete Broadcast on deleteBroadcastByRequestId: ' . $exception->getMessage()];
        }
    }


    /**
     * Gets a list of Broadcast loaded per broadcast_id
     * @param int $broadcastId
     *
     * @return Array
     */
    public static function getBroadcastFiles(int $broadcastId)
    {
        try {
            $fileId = PinnacleBroadcast::where('broadcast_id', $broadcastId)
            ->first();
            $idFiles = $fileId->files;

            if (!empty($idFiles)) {
                $file = DB::table('media')
                ->whereIn('media.id', $idFiles)
                ->where('media.model_id', $fileId->request_id)
                ->get()
                ->toArray();
                return $file;
            }
            return [];
        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'There are errors in the Function: getBroadcastFiles ' . $exception->getMessage()];
        }
    }


    /**
     * Store a record on pinnacle_broadcast
     * @param Array $data
     *
     * @return Array
     */
    public function storeBroadcast(Array $data)
    {
        try {
            if (empty($data['broadcast_id'])) {
                $this->fill($data['data']);
                $this->saveOrFail();
            } else {
                $this->where($data['broadcast_id'], 'broadcast_id')->update($data);
            }
            return $this;
        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to save Broadcast on store: ' . $exception->getMessage()];
        }
    }

    /**
     * Get Broadcast data by broadcast_id
     * @param string $broadcastId
     *
     * @return Array
     */
    static function getBroadcastById ($broadcastId = '')
    {
        try {
            if (empty($broadcastId)) {
                throw new Exception('The broadcast ID is required. ');
            }
            $broadcast = PinnacleBroadcast::where('broadcast_id', $broadcastId)
            ->first();
            return $broadcast;

        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to get Broadcast on getBroadcastById: ' . $exception->getMessage()];
        }
    }

    /**
     * Get all Broadcast of Request
     * @param String $requestId
     *
     * @return Array
     */
    public function getAllBroadcastByRequestId($requestId)
    {
        if (empty($requestId)) {
            throw new Exception('The request ID is required. ');
        }
        try {
            $broadcast = PinnacleBroadcast::where('request_id', $requestId)
            ->get();

            return $broadcast;
        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to get Broadcast list on getAllBroadcastByRequestId: ' . $exception->getMessage()];
        }
    }

    /**
     * Get list of Broadcast with filters 'status', 'request_id', 'dateToSend', 'timeToSend'
     * @param Array $data
     *
     * @return Array
     */
    public function getBroadcastFilters(Array $data = [])
    {
        try {
            $status     = $data['data']['status'] ?? '';
            $requestId  = $data['data']['requestId'] ?? '';
            $dateToSend = $data['data']['dateToSend'] ?? '' ;
            $timeToSend = $data['data']['timeToSend'] ?? '00:00' ;

            $broadcast = static::
            when($status, function ($query, $status){
                return $query->where('status', $status);
            })
            ->when($requestId, function ($query, $requestId){
                return $query->where('request_id', $requestId);
            })
            ->when($dateToSend, function ($query) use ($dateToSend, $timeToSend)
            {
                return $query->where('date_to_send', $dateToSend)
                ->where('time_to_send', '<', $timeToSend);
            })
            ->get();
            return $broadcast;
        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to get Broadcast list on getBroadcastFilters: ' . $exception->getMessage()];
        }
    }

    /**
     * Update Broadcast by broadcast_id
     * @param Array $data
     * @param String $broadcastId
     *
     * @return Array
     */
    public function updateBroadcast(Array $data, $broadcastId)
    {
        try {
            if (empty($data)) {
                throw new Exception('Nothing to update. ');
            }

            $pinnacleBroadcast = static::where('broadcast_id', $broadcastId)->update($data);
            return $pinnacleBroadcast;
        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to update Broadcast on function update: ' . $exception->getMessage()];
        }
    }
}
