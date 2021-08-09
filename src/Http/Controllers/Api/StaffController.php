<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers\Api;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackagePinnacle\Models\Staff;
use RBAC;
use Illuminate\Http\Request;
use URL;


class StaffController extends Controller
{

    /**
     * Get employee by employeeNo
     * @param String $employeeNo
     *
     * @return object
     */
    public function getStaffByEmployeeNo(String $employeeNo)
    {
        try {
            $staff = Staff::getStaffByEmployeeNo($employeeNo);

            return $staff;
        } catch (Exception $exception) {
            throw new Exception('Error function getStaffByEmployeeNo: ' .$exception->getMessage());
        }
    }

    public function getStaffAndGroups()
    {
        try {
            $staffList = Staff::getStaffAndGroups();
            return $staffList;
        } catch (Exception $exception) {
            throw new Exception("Error getStaffAndGroups controller: " . $exception->getMessage());
        }
    }
}
