<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers\Api;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackagePinnacle\Models\Student;
use RBAC;
use Illuminate\Http\Request;
use URL;


class StudentController extends Controller
{
    public function getParentDataByStudentNo(String $studentNo)
    {
        try {
            $parents = Student::getParentDataByStudentNo($studentNo);

            return $parents;
        } catch (Exception $exception) {
            throw new Exception('Error on getParentDataByStudentNo function: ' . $exception->getMessage());
        }
    }
}