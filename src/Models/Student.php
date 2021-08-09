<?php

namespace ProcessMaker\Package\PackagePinnacle\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use \DB;

class Student extends Model
{
    protected $table = 'uvProcessMakerStudents';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'studentNo',
        'firstName',
        'lastName',
        'fullName',
        'email',
        'username',
        'dateOfBirth',
        'gender',
        'picture',
        'gradeGroup',
        'gradeLevel',
        'locationId',
        'locationName',
        'status',
        'createdDate',
        'updatedDate'
    ];

    public static function insertStudentData($data)
    {
        try {
            return static::insertGetId($data);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function updateStudentData($id, $data)
    {
        try {
            return static::where('id', '=', $id)->update($data);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function existsStudent($studentNo, $gradeGroup, $locationId)
    {
        try {
            $query = static::where('studentNo', '=', $studentNo)
                ->whereRaw("UPPER(`gradeGroup`) = '" . strtoupper(trim($gradeGroup)) . "'")
                ->where('locationId', '=', $locationId)
                ->first();
            $result = !empty($query) ? $query->id : false;

            return $result;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getStudents()
    {
        try {
            // $query = static::select(DB::raw('DISTINCT studentNo, fullName'))
            $query = static::distinct('studentNo', 'fullName')
                ->where('status', '=', 'ACT')
				->orderBy('fullName', 'asc')
                ->select('studentNo', 'fullName')
                ->get()->toArray();


            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getStudentInfo($studentNo)
    {
        try {
            $query = static::where('studentNo', $studentNo)
                ->orderBy('updatedDate', 'desc')
                ->where('status', '=', 'ACT')
                ->first();
            $result = empty($query) ? [] : $query;

            return $result;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getGradeGroup()
    {
        try {
            $query = static::select(DB::raw('gradeGroup, CONCAT(gradeGroup, " (", COUNT(gradeGroup), ")") AS gradeGroupLabel'))
                ->groupBy('gradeGroup')
				->where('status', '=', 'ACT')
                ->orderBy('gradeGroup', 'asc')
                ->get()->toArray();

            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getGradeGroupByCampus($campus)
    {
        try {
            $query = static::select(DB::raw('gradeGroup, CONCAT(gradeGroup, " (", COUNT(gradeGroup), ")") AS gradeGroupLabel'))
                ->groupBy('gradeGroup')
                ->where('status', '=', 'ACT')
                ->where('locationId', '=', $campus)
                ->orderBy('gradeGroup', 'asc')
                ->get()->toArray();

            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

	public static function getStudentsIdByGrade($gradeGroup)
    {
        try {
            // $query = static::select(DB::raw('DISTINCT studentNo, fullName'))
            $query = static::distinct('studentNo', 'fullName')
                ->where('gradeGroup', '=', $gradeGroup)
                ->select('studentNo', 'fullName')
                ->get()->toArray();

            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getStudentsIdByGradeAndCampus($gradeGroup, $locationId)
    {
        try {
            // $query = static::select(DB::raw('DISTINCT studentNo, fullName'))
            $query = static::distinct('studentNo', 'fullName')
                ->where('gradeGroup', '=', $gradeGroup)
                ->where('locationId', '=', $locationId)
                ->where('status', '=', 'ACT')
                ->select('studentNo', 'fullName')
                ->orderBy('fullName', 'asc')
                ->get()->toArray();

            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getStudentsIdByCampus($campus)
    {
        try {
            // $query = static::select(DB::raw('DISTINCT studentNo, fullName'))
            $query = static::distinct('studentNo', 'fullName')
                ->where('locationId', '=', $campus)
                ->where('status', '=', 'ACT')
                ->select('studentNo', 'fullName')
                ->orderBy('fullName', 'ASC')
                ->get()->toArray();

            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getStudentParentEmails($studentNo)
    {
        try {
            $query = DB::table('uvProcessMakerStudentParents')
                // ->select(DB::raw('DISTINCT parentNo, studentNo, parentRelation'))
                ->distinct('parentNo', 'studentNo', 'parentRelation')
                ->select('parentNo', 'studentNo', 'parentRelation')
                ->where('studentNo', '=', $studentNo);
            $results = $query->get();

            ////---- transforming with additional data
            $results->transform(function ($item, $key) use ($studentNo) {
                $item = json_decode(json_encode($item), true);
                ////----setting last email from student
                if($item['studentNo']) {
                    $studentInfo = static::where('studentNo', $studentNo)->orderBy('updatedDate', 'desc')->first();
                    $item['studentEmail'] = $studentInfo['email'];
                    $item['studentFullName'] = $studentInfo['fullName'];
                }
                ////----setting last email from parent
                if($item['parentNo']) {
                    $parentInfo = Parents::where('parentNo', $item['parentNo'])->orderBy('updatedDate', 'desc')->first();
                    $item['parentEmail'] = $parentInfo['email'];
                    $item['parentFullName'] = $parentInfo['fullName'];
                    $item['mobilePhone'] = $parentInfo['mobilePhone'];
                }

                return $item;
            });
            ////---- removing any empty erroneous data
            $results = $results->filter();
            $results = $results->values();

            return empty($results) ? [] : $results;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

	public static function getCampuses()
    {
        try {
            $query = static::select('locationName')
                ->groupBy('locationName');
			$results = $query->get();
			////---- transforming with additional data
            $results->transform(function ($item, $key) {
                $item = json_decode(json_encode($item), true);
                ////----setting last email from student
                if($item['locationName']) {
                    $studentInfo = static::where('locationName', $item['locationName'])->first();
                    $item['locationId'] = $studentInfo['locationId'];
                }

                return $item;
            });

            ////---- removing any empty erroneous data
            $results = $results->filter();
            $results = $results->values();

            return empty($results) ? [] : $results;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public static function getParentDataByStudentNo(String $studentNo)
    {
        try {
            // $parents = static::select(DB::raw('DISTINCT uvProcessMakerStudentParents.parentNo') , 'uvProcessMakerParents.*')
            $parents = static::distinct('DISTINCT uvProcessMakerStudentParents.parentNo')
            ->join('uvProcessMakerStudentParents', 'uvProcessMakerStudents.studentNo', '=', 'uvProcessMakerStudentParents.studentNo')
            ->join('uvProcessMakerParents', 'uvProcessMakerStudentParents.parentNo', '=', 'uvProcessMakerParents.parentNo')
            ->where('uvProcessMakerStudentParents.studentNo', $studentNo)
            ->where('uvProcessMakerStudents.status', 'ACT')
            ->where('uvProcessMakerParents.status', 'ACT')
            ->select('uvProcessMakerStudentParents.parentNo', 'uvProcessMakerParents.*')
            ->distinct()
            ->get()
            ->toArray();

            return $parents;
        } catch (Exception $exception) {
            throw new Exception('Error function getParentDataByStudentNo: ' . $exception->getMessage());

        }
    }
}
