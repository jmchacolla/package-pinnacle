<?php

namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers\Api;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Package\PackagePinnacle\Models\PinnacleBroadcast;
use ProcessMaker\Package\PackagePinnacle\Models\Staff;
use Illuminate\Http\Request;
use \Exception;
use \DB;
use RBAC;
use URL;


class PinnacleBroadcastController extends Controller
{

    /**
     * Controller to save Broadcast
     * @param Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function storeBroadcast(Request $request)
    {
        try {
            if (empty($request->all())) {
                throw new Exception('Nothing to update. ');
            }
            $pinnacleBroadcast = new PinnacleBroadcast();
            $pinnacleBroadcast->storeBroadcast($request->all());

            if ($pinnacleBroadcast['error']) {
                throw new Exception('Error to save Broadcast: ' . $pinnacleBroadcast['message']);
            }
            return $pinnacleBroadcast;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => 'Error to Save Broadcast: ' . $exception], 500);
        }
    }

    /**
     * Controller to get  Broadcasts by Request
     * @param int $requestId
     *
     * @return Illuminate\Http\Response
     */
    public function getAllBroadcastByRequestId(int $requestId = null)
    {
        try {
            if (empty($requestId)) {
                throw new Exception('The resuest ID is required. ');
            }
            $broadcast = new PinnacleBroadcast();
            $response = $broadcast->getAllBroadcastByRequestId($requestId);
            if ($response['error']) {
                throw new Exception('Error to get Broadcast: ' . $response['messge']);
            }
            return $response;
        } catch (Exception $exception) {
            return response()->json(['data' => null, 'error' => true, 'message' => 'Error function getBroadcastByRequestId: ' . $exception->getMessage()]);
        }
    }

    /**
     * Controller to get Broadcast by broadcast_id
     * @param string $broadcastId
     *
     * @return Illuminate\Http\Response
     */
    static function getBroadcastById(String  $broadcastId = '')
    {
        try {
            if (empty($broadcastId)) {
                throw new Exception('The broadcast ID is required. ');
            }
            $broadcast = new PinnacleBroadcast();
            $response  = $broadcast->getBroadcastById($broadcastId);
            if ($response['error']) {
                throw new Exception($response['message']);
            }
            return $response;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => 'Error to get Broadcast on getBroadcastById: ' . $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function getBroadcastByStatus(Request $request = null)
    {
        try {
            $broadcast = new PinnacleBroadcast();
            $response  = $broadcast->getBroadcastFilters($request->all());
            if($response['error']) {
                throw new Exception($response['message']);
            }
            return $response;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => 'There are an error on function getBroadcastByStatus: ' . $exception->getMessage()]);
        }
    }

    /**
     * Update Broadcast
     * @param \Illuminate\Http\Request|null $request
     * @param String $broadcastId
     *
     * @return Illuminate\Http\Response
     */
    public function updateBroadcast(Request $request)
    {
        try {
            if (empty($request->all())) {
                throw new Exception('Nothing to update.-kokokokook');
            }
            if (empty($request->all()['data']['broadcast_id'])) {
                throw new Exception('The Broadcast ID is Required. ');
            }
            $pinnacleBroadcast = new PinnacleBroadcast();
            $response          = $pinnacleBroadcast->updateBroadcast($request->all()['data'], $request->all()['data']['broadcast_id']);
            if($response['error']) {
                throw new Exception($response['message']);
            }
            return $response;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => 'Error to update Broadcast on function update: ' . $exception->getMessage()]);
        }
    }

    /**
     * Get List of Files Uploaded from broadcast form
     * @param int $broadcastId
     *
     * @return Illuminate\Http\Response
     */
    public function getBroadcastFiles(int $broadcastId)
    {
        try {
            $files = PinnacleBroadcast::getBroadcastFiles($broadcastId);
            // if ($files['error']) {
            //     throw new Exception($files['message']);
            // }
            return $files;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => 'There are errors in the Function getBroadcastFiles: ' . $exception->getMessage()]);
        }
    }
}

