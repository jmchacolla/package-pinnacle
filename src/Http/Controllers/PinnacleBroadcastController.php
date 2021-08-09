<?php

namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackagePinnacle\Models\PinnacleBroadcast;
use ProcessMaker\Package\PackagePinnacle\Models\Staff;
use ProcessMaker\Models\EnvironmentVariable;
use Illuminate\Http\Request;
use \Exception;
use RBAC;
use \DB;
use URL;

class PinnacleBroadcastController extends Controller
{

    /**
     * Form Edit Broadcast
     * @param Request|null $request
     * @param String $broadcastId
     *
     * @return mixed
     */
    public function editBroadcast( $broadcastId = '')
    {
        try {
            if (empty($broadcastId)) {
                throw new Exception('The Broadcast ID is required. ');
            }
            $broadcast          = new PinnacleBroadcast();
            $broadcastResponse  = $broadcast->getBroadcastById($broadcastId);

            if ($broadcastResponse['error']) {
                throw new Exception('Error to get Broadcast. ');
            }

            $files         = $broadcast->getBroadcastFiles($broadcastId);
            $staffList     = Staff::getStaffAndGroups();
            $payWayBaseUrl = EnvironmentVariable::whereName('PAYWAY_BASE_URL')->first()->value;
            $apiToken      = EnvironmentVariable::whereName('PINN_API_TOKEN')->first()->value;

            $varsJs = [
                'broadcast'  => $broadcastResponse->toArray(),
                'files'      => $files,
                'staff_list' => $staffList,
                'apiToken'   => $apiToken
            ];

            return view('package-pinnacle::editBroadcast', compact('varsJs'));
        } catch (Exception $exception) {
            return redirect()->route('package.pinnacle.tab.pinnacle-report');
        }
    }

    /**
     * Return Form to View Broadcast by ID
     * @param int $id
     *
     * @return mixed
     */
    public function view(int $id)
    {
        try {
            $pinnacleBroadcast = PinnacleBroadcast::getBroadcastById($id);
            $files             = PinnacleBroadcast::getBroadcastFiles($id);
            $staffList         = Staff::getStaffAndGroups();

            $varsJs = [
                'pinnacleBroadcast' => json_decode($pinnacleBroadcast, true),
                'staff_list'        => $staffList,
                'files'             => $files
            ];

            return view('package-pinnacle::viewBroadcast', compact('varsJs'));

        } catch (Exception $exception) {
            throw new Exception("Error the get view store" . $exception->getMessage());
        }
    }
}