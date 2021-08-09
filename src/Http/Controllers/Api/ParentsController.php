<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers\Api;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackagePinnacle\Models\Parents;
use RBAC;
use Illuminate\Http\Request;
use URL;


class ParentsController extends Controller
{
    /**
     * Get parents of student by sudentNo
     * @param String $studentNo
     *
     * @return collection
     */
    public function getParentDataByStudentNo(String $studentNo)
    {
        try {
            $parents = Parents::getParentDataByStudentNo($studentNo);

            return $parents;
        } catch (Exception $exception) {
            throw new Exception('Error on getParentDataByStudentNo: ' . $exception->getMessage());
        }
    }
}