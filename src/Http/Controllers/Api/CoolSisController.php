<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use PDO;
use PDOException;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Package\PackagePinnacle\Models\Parents;
use ProcessMaker\Package\PackagePinnacle\Models\Staff;
use ProcessMaker\Package\PackagePinnacle\Models\Student;
use ProcessMaker\Package\PackagePinnacle\Models\StudentParents;

class CoolSisController extends Controller
{
    private $conn;

    public function index(){
        return 'no views';
    }

    /**
     * Get PDO connection
     * @return object
     */
    public function getConnection()
    {
        ////---- setting coolSis connection data
        $user = getenv('COOLSIS_USER');
        $pass = getenv('COOLSIS_PWD');
        $host = getenv('COOLSIS_HOST');
        $port = getenv('COOLSIS_PORT');
        $db   = getenv('COOLSIS_DB');
        $dsn  = "sqlsrv:server={$host},{$port};Database={$db}";
        $connection = new PDO($dsn, $user, $pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);

        return $connection;
    }

    /**
     * Execute a PDO query
     * @param mixed $statement
     *
     * @return array
     */
    public function executeQuery($statement)
    {
        $result = [];
        //$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        switch (true) {
            case preg_match( "/^(SELECT|EXECUTE|EXEC|SHOW|DESCRIBE|EXPLAIN|BEGIN)\s/i", $statement):
                $sth = $this->conn->prepare($statement);
                $sth->execute();
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                break;
            case preg_match("/^(INSERT|UPDATE|DELETE|REPLACE)\s/i", $statement):
                $this->conn->beginTransaction();
                $sth = $this->conn->prepare($statement);
                $sth->execute();
                $this->conn->commit();
                break;
        }
        $error = $sth->errorInfo();
        if (!empty($error[2]) && $error[2] !== null) {
            throw new Exception($error[2]);
        }

        return $result;
    }

    /**
     * Test PDO conecction
     * @return Response
     */
    public function testConnection()
    {
        try {
            $connection = $this->getConnection();
            $error = $connection->errorInfo();
            if (!empty($error[2]) && $error[2] !== null) {
                throw new Exception($error[2]);
            }
            $connection = null;

            return response()->json(['connStatus' => true, 'data' => [], 'message' => 'Connection tested successfully.']);
        } catch (PDOException $exception) {
            return response()->json(['connStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Get all CoolSis parents
     * @return Response
     */
    public function getCoolSisParents()
    {
        try {
            $sql = "SELECT * FROM uvProcessMakerParents;";
            $this->conn = $this->getConnection();
            $result = $this->executeQuery($sql);
            $this->conn = null;

            return response()->json(['resultStatus' => true, 'data' => $result, 'message' => 'Success CoolSisParents.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Get all CoolSis Staff
     * @return Response
     */
    public function getCoolSisStaff()
    {
        try {
            $sql = "SELECT * FROM uvProcessMakerStaff;";
            $this->conn = $this->getConnection();
            $result = $this->executeQuery($sql);
            $this->conn = null;

            return response()->json(['resultStatus' => true, 'data' => $result, 'message' => 'Success CoolSisStaff.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Get all CoolSis Students
     * @return Response
     */
    public function getTotalCoolSisStudents()
    {
        try {
            $sql = "SELECT COUNT(*) AS total FROM uvProcessMakerStudents;";
            $this->conn = $this->getConnection();
            $result = $this->executeQuery($sql);
            $this->conn = null;

            return response()->json(['resultStatus' => true, 'data' => $result, 'message' => 'Success Total CoolSisStudents.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function getCoolSisStudents($offset, $fetch)
    {
        try {
            $sql = "SELECT studentNo, firstName, lastName, fullName, email, username, dateOfBirth, gender, ";
            $sql .= "''+(select picture as '*' for xml path('')) AS picture, ";
            $sql .= "gradeGroup, gradeLevel, locationId, locationName, status, createdDate, updatedDate FROM uvProcessMakerStudents ";
            $sql .= "ORDER BY studentNo ASC OFFSET " . $offset . " ROWS FETCH NEXT " . $fetch . " ROWS ONLY;";
            $this->conn = $this->getConnection();
            $result = $this->executeQuery($sql);
            $this->conn = null;

            return response()->json(['resultStatus' => true, 'data' => $result, 'message' => 'Success CoolSisStudents.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function getCoolSisStudentParents()
    {
        try {
            $sql = "SELECT * FROM uvProcessMakerStudentParents;";
            $this->conn = $this->getConnection();
            $result = $this->executeQuery($sql);
            $this->conn = null;

            return response()->json(['resultStatus' => true, 'data' => $result, 'message' => 'Success CoolSisStudentParents.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function syncParents()
    {
        try {
            ////---- getting coolSis parents
            $responseData = [];
            $coolSisParents = $this->getCoolSisParents();
            $coolSisParentsArray = $coolSisParents->original;
            if ($coolSisParentsArray['resultStatus'] && !empty($coolSisParentsArray['data'])) {
                $responseData['updates'] = [];
                $responseData['inserts'] = [];
                foreach ($coolSisParentsArray['data'] as $value) {
                    $parentId = Parents::existsParent($value['parentNo'], $value['locationId']);
                    if ($parentId !== false) {
                        Parents::updateParentData($parentId, $value);
                        $responseData['updates'][] = [
                            'rowId' => $parentId,
                            'parentNo' => $value['parentNo'],
                            'locationId' => $value['locationId']
                        ];
                    } else {
                        $parentId = Parents::insertParentData($value);
                        $responseData['inserts'][] = [
                            'rowId' => $parentId,
                            'parentNo' => $value['parentNo'],
                            'locationId' => $value['locationId']
                        ];
                    }
                }
                $totalInserts = count($responseData['inserts']);
                $totalUpdates = count($responseData['updates']);
                $responseData['totalInserts'] = $totalInserts;
                $responseData['totalUpdates'] = $totalUpdates;
                $responseData['total'] = (int) $totalInserts + $totalUpdates;
            } else {
                throw new Exception('No Rows found in coolSis Parents.');
            }

            return response()->json(['resultStatus' => true, 'data' => $responseData, 'message' => 'The Parents table was synchronized successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function syncStaff()
    {
        try {
            ////---- getting coolSis staff
            $responseData = [];
            $coolSisStaff = $this->getCoolSisStaff();
            $coolSisStaffArray = $coolSisStaff->original;
            if ($coolSisStaffArray['resultStatus'] && !empty($coolSisStaffArray['data'])) {
                $responseData['updates'] = [];
                $responseData['inserts'] = [];
                foreach ($coolSisStaffArray['data'] as $value) {
                    $staffId = Staff::existsStaff($value['employeeNo'], $value['locationId']);
                    if ($staffId !== false) {
                        Staff::updateStaffData($staffId, $value);
                        $responseData['updates'][] = [
                            'rowId' => $staffId,
                            'employeeNo' => $value['employeeNo'],
                            'locationId' => $value['locationId']
                        ];
                    } else {
                        $staffId = Staff::insertStaffData($value);
                        $responseData['inserts'][] = [
                            'rowId' => $staffId,
                            'employeeNo' => $value['employeeNo'],
                            'locationId' => $value['locationId']
                        ];
                    }
                }
                $totalInserts = count($responseData['inserts']);
                $totalUpdates = count($responseData['updates']);
                $responseData['totalInserts'] = $totalInserts;
                $responseData['totalUpdates'] = $totalUpdates;
                $responseData['total'] = (int) $totalInserts + $totalUpdates;
            } else {
                throw new Exception('No Rows found in coolSis Staff.');
            }

            return response()->json(['resultStatus' => true, 'data' => $responseData, 'message' => 'The Staff table was synchronized successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function syncStudents()
    {
        try {
            ini_set('memory_limit', '-1');
            ini_set('set_time_limit', 0);
            ini_set('max_execution_time', 0);
            ////---- getting total coolSis students
            $total = 0;
            $totalStudents = $this->getTotalCoolSisStudents();
            $totalStudents = $totalStudents->original;
            if ($totalStudents['resultStatus'] && !empty($totalStudents['data'])) {
                $total = (int) $totalStudents['data']['0']['total'];
            }

            ////---- grouping by 200 rows
            $limit = 200;
            $offset = 0;
            $fetch = $limit;
            ////---- calculating number of blocks
            $blocksNumber = (int) ceil($total / $limit);
            $responseData = [];
            $responseData['updates'] = [];
            $responseData['inserts'] = [];
            ////---- getting students (block by block: block of 200 rows)
            for ($index = 1; $index <= $blocksNumber; $index++) {
                $coolSisStudent = $this->getCoolSisStudents($offset, $fetch);
                $coolSisStudentArray = $coolSisStudent->original;
                if ($coolSisStudentArray['resultStatus'] && !empty($coolSisStudentArray['data'])) {
                    foreach ($coolSisStudentArray['data'] as $value) {
                        $studentId = Student::existsStudent($value['studentNo'], $value['gradeGroup'], $value['locationId']);
                        if ($studentId !== false) {
                            Student::updateStudentData($studentId, $value);
                            $responseData['updates'][] = [
                                'rowId' => $studentId,
                                'studentNo' => $value['studentNo'],
                                'gradeGroup' => $value['gradeGroup']
                            ];
                        } else {
                            $studentId = Student::insertStudentData($value);
                            $responseData['inserts'][] = [
                                'rowId' => $studentId,
                                'studentNo' => $value['studentNo'],
                                'gradeGroup' => $value['gradeGroup']
                            ];
                        }
                    }
                } else {
                    throw new Exception('No Rows found in coolSis Students.');
                }
                $offset = $offset + $fetch;
            }
            $totalInserts = count($responseData['inserts']);
            $totalUpdates = count($responseData['updates']);
            $responseData['totalInserts'] = $totalInserts;
            $responseData['totalUpdates'] = $totalUpdates;
            $responseData['total'] = (int) $totalInserts + $totalUpdates;

            return response()->json(['resultStatus' => true, 'data' => $responseData, 'message' => 'The Students table was synchronized successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function syncStudentParents()
    {
        try {
            ////---- getting coolSis student-parents
            $responseData = [];
            $coolSisStudentParents = $this->getCoolSisStudentParents();
            $coolSisStudentParentsArray = $coolSisStudentParents->original;
            if ($coolSisStudentParentsArray['resultStatus'] && !empty($coolSisStudentParentsArray['data'])) {
                $responseData['updates'] = [];
                $responseData['inserts'] = [];
                foreach ($coolSisStudentParentsArray['data'] as $value) {
                    $exists = StudentParents::existsStudentParents($value['studentNo'], $value['parentNo']);
                    if ($exists) {
                        StudentParents::updateStudentParentsData($value['studentNo'], $value['parentNo'], $value);
                        $responseData['updates'][] = [
                            'studentNo' => $value['studentNo'],
                            'parentNo' => $value['parentNo'],
                            'parentRelation' => $value['parentRelation']
                        ];
                    } else {
                        StudentParents::insertStudentParentsData($value);
                        $responseData['inserts'][] = [
                            'studentNo' => $value['studentNo'],
                            'parentNo' => $value['parentNo'],
                            'parentRelation' => $value['parentRelation']
                        ];
                    }
                }
                $totalInserts = count($responseData['inserts']);
                $totalUpdates = count($responseData['updates']);
                $responseData['totalInserts'] = $totalInserts;
                $responseData['totalUpdates'] = $totalUpdates;
                $responseData['total'] = (int) $totalInserts + $totalUpdates;
            } else {
                throw new Exception('No Rows found in coolSis StudentParents.');
            }

            return response()->json(['resultStatus' => true, 'data' => $responseData, 'message' => 'The StudentParents table was synchronized successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function getStudents()
    {
        try {
            $students = Student::getStudents();

            return response()->json(['resultStatus' => true, 'data' => $students, 'total' => count($students), 'message' => 'Students was list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'total' => 0, 'message' => $exception->getMessage()]);
        }
    }

    public function getStudentInfo($studentNo)
    {
        try {
            $studentInfo = Student::getStudentInfo($studentNo);

            return response()->json(['resultStatus' => true, 'data' => $studentInfo, 'message' => 'Student Info was loaded successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function getParentInfo($parentNo)
    {
        try {
            $parentInfo = Parents::getParentInfo($parentNo);

            return response()->json(['resultStatus' => true, 'data' => $parentInfo, 'message' => 'Parent Info was loaded successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function getGradeGroup()
    {
        try {
            $gradeGroup = Student::getGradeGroup();

            return response()->json(['resultStatus' => true, 'data' => $gradeGroup, 'total' => count($gradeGroup), 'message' => 'All Grades was list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'total' => 0, 'message' => $exception->getMessage()]);
        }
    }

	public function getGradeGroupByCampus($campus)
    {
        try {
            $gradeGroup = Student::getGradeGroupByCampus($campus);

            return response()->json(['resultStatus' => true, 'data' => $gradeGroup, 'total' => count($gradeGroup), 'message' => 'All Grades was list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'total' => 0, 'message' => $exception->getMessage()]);
        }
    }

    public function getStudentsIdByGrade($grade)
    {
        try {
            $studentsByGrade = Student::getStudentsIdByGrade($grade);

            return response()->json(['resultStatus' => true, 'data' => $studentsByGrade, 'message' => 'Students by Grade were list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function getStudentsIdByGradeAndCampus($grade, $campus)
    {
        try {
            $studentsByGrade = Student::getStudentsIdByGradeAndCampus($grade, $campus);

            return response()->json(['resultStatus' => true, 'data' => $studentsByGrade, 'message' => 'Students by grade and campus were list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

	public function getStudentsIdByCampus($campus)
    {
        try {
            $studentsByCampus = Student::getStudentsIdByCampus($campus);

            return response()->json(['resultStatus' => true, 'data' => $studentsByCampus, 'total' => count($studentsByCampus), 'message' => 'Students by Campus were list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'total' => 0, 'message' => $exception->getMessage()]);
        }
    }

    public function getStudentParentEmails($studentNo)
    {
        try {
            $result = Student::getStudentParentEmails($studentNo);

            return response()->json(['resultStatus' => true, 'data' => $result, 'message' => 'Students by Grade were list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }

    public function getAllCampus()
    {
        try {
            $result = Student::getCampuses();

            return response()->json(['resultStatus' => true, 'data' => $result, 'message' => 'Campuses were list successfully.']);
        } catch (Exception $exception) {
            return response()->json(['resultStatus' => false, 'data' => [], 'message' => $exception->getMessage()]);
        }
    }



}
