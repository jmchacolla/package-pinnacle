<?php
Route::group(['middleware' => ['auth:api', 'bindings']], function() {

    // Process Request
    Route::get('pinnacle/clone-request/{requestId}', 'Api\ProcessRequestController@cloneRequest')->where('requestId', '[0-9]+');
    Route::get('pinnacle/process-request/files/{id}', 'Api\ProcessRequestController@getRequestFiles');
    Route::put('pinnacle/process-request-data/{id}', 'Api\ProcessRequestController@updateProcessRequestData');
    Route::put('pinnacle/process-request/{id}', 'Api\ProcessRequestController@updateProcessRequest');
    Route::delete('pinnacle/process-request/{caseId}', 'Api\ProcessRequestController@deleteSLip');

    ////---- PM CoolSis APIs
    Route::get('pinnacle/pmcoolsis/students', 'Api\CoolSisController@getStudents');
    Route::get('pinnacle/pmcoolsis/studentInfo/{studentNo}', 'Api\CoolSisController@getStudentInfo')->where('studentNo', '[0-9]+');
    Route::get('pinnacle/pmcoolsis/parentInfo/{parentNo}', 'Api\CoolSisController@getParentInfo')->where('parentNo', '[0-9]+');
    Route::get('pinnacle/pmcoolsis/grades', 'Api\CoolSisController@getGradeGroup');
    Route::get('pinnacle/pmcoolsis/grades-campus/{locationId}', 'Api\CoolSisController@getGradeGroupByCampus')->where('locationId', '[0-9]+');
    Route::get('pinnacle/pmcoolsis/studentsByGrade/{gradeCode}', 'Api\CoolSisController@getStudentsIdByGrade')->where('gradeCode', '[0-9A-Z\/\-]+');
    Route::get('pinnacle/pmcoolsis/studentsByGradeAndCampus/{gradeCode}/{locationId}', 'Api\CoolSisController@getStudentsIdByGradeAndCampus')->where('gradeCode', '[0-9A-Z\/\-]+')->where('locationId', '[0-9]+');
    Route::get('pinnacle/pmcoolsis/studentsByCampus/{locationId}', 'Api\CoolSisController@getStudentsIdByCampus')->where('locationId', '[0-9]+');
    Route::get('pinnacle/pmcoolsis/studentParentEmails/{studentNo}', 'Api\CoolSisController@getStudentParentEmails')->where('studentNo', '[0-9]+');
    Route::get('pinnacle/pmcoolsis/campuses', 'Api\CoolSisController@getAllCampus');
    Route::get('pinnacle/pmcoolsis/staff/{employeeNo}', 'Api\StaffController@getStaffByEmployeeNo');
    Route::get('pinnacle/staff-groups', 'Api\StaffController@getStaffAndGroups');
    Route::get('pinnacle/pmcoolsis/student-parents-data/{studentNo}', 'Api\StudentController@getParentDataByStudentNo')->where('studentNo', '[0-9]+');

    // Payment Record
    Route::get('pinnacle/payment-record/{caseId}', 'Api\PinnaclePaymentRecordController@getPaymentsByCaseId');
    Route::put('pinnacle/payment-record/{id}', 'Api\PinnaclePaymentRecordController@update');
    Route::post('pinnacle/payment-record-massive/{caseId}', 'Api\PinnaclePaymentRecordController@createMassive');
    Route::post('pinnacle/payment-record-delete-massive/{caseId}', 'Api\PinnaclePaymentRecordController@deleteMassive');
    Route::get('pinnacle/payment-record-by-student/{studentNo}', 'Api\PinnaclePaymentRecordController@getPaymentRecordByStudentNo');
    Route::get('pinnacle/student-slip-detail/{id}/{caseid}', 'Api\PinnaclePaymentRecordController@getStudentSlipDetail');
});
