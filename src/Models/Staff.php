<?php

namespace ProcessMaker\Package\PackagePinnacle\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use \DB;

class Staff extends Model
{
    protected $table = 'uvProcessMakerStaff';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'employeeNo',
        'firstName',
        'lastName',
        'fullName',
        'gender',
        'username',
        'workEmail',
        'phone',
        'locationId',
        'locationName',
        'status',
        'createdDate',
        'updatedDate'
    ];

    public static function insertStaffData($data)
    {
        try {
            return static::insertGetId($data);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public static function updateStaffData($id, $data)
    {
        try {
            return static::where('id', '=', $id)->update($data);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public static function existsStaff($employeeNo, $locationId)
    {
        try {
            $query = static::where('employeeNo', '=', $employeeNo)
                ->where('locationId', '=', $locationId)
                ->first();
            $result = !empty($query) ? $query->id : false;

            return $result;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public static function getAllStaff()
    {
        try {
            $query = static::select(DB::raw('DISTINCT employeeNo, fullName'))
                ->where('status', '=', 'Act')
				->orderBy('fullName', 'asc')
                ->get()->toArray();
            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getStaffByEmployeeNo(String $employeeNo)
    {
        try {
            $staff = static::where('employeeNo', '=', $employeeNo)
            ->get()
            ->toArray();

            return $staff;

        } catch (Exception $exception) {
            throw new Exception('Error function getStaffByEmployeeNo: ' . $exception->getMessage());
        }
    }

    public static function getStaffAndGroups()
    {
        try {
            $staffList = static::select(DB::raw('DISTINCT employeeNo, fullName, workEmail'))
            ->where('status', '=', 'Act')
            ->orderBy('fullName', 'asc')
            ->get()->toArray();
            $staffListWithType = [];
            foreach ($staffList as $key => $value) {
                $value['type'] = 'STAFF';
                array_push($staffListWithType, $value);
            }

            $groups = DB::table('groups')
            ->select('group_members.group_id AS employeeNo', DB::raw("CONCAT (groups.name, ' (',  count(*) , ')') AS fullName"))
            ->join('group_members', 'groups.id', '=', 'group_members.group_id')
            ->where('status', 'ACTIVE')
            ->groupBy('group_members.group_id')
            ->get()
            ->toArray();
            $groups = json_decode(json_encode($groups), true);

            $groupsWithType = [];
            foreach ($groups as $key => $value) {
                $value['type'] = 'GROUP';
                array_push($groupsWithType, $value);
            }

            $gruopAndStaff = array_merge($staffListWithType, $groupsWithType);

            return $gruopAndStaff;
        } catch (Exception $exception) {
            throw new Exception("Error getStaffAndGroups: " . $exception->getMessage());
        }
    }

}
