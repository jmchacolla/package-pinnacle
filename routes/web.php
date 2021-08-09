<?php

Route::group(['middleware' => ['auth']], function () {
    Route::get('pinnacle/request', 'ProcessRequestController@showProcessRequestList')->name('package.pinnacle.tab.pinnacle-report');
    Route::get('pinnacle/pinnacle-request/{caseId}', 'ProcessRequestController@showProcessRequest');

    // Broadcast
    Route::get('pinnacle/broadcast/{broadcastId}', 'PinnacleBroadcastController@editBroadcast')->name('pinnacle.broadcast');
    Route::get('pinnacle/broadcast/view/{id}', 'PinnacleBroadcastController@view')->name('pinnacle.broadcast');

});

// Print permisions forms
Route::get('pinnacle/print-form/{caseId}', 'ParentApprovalController@printForms');
Route::get('pinnacle/print-blank-form/{caseId}', 'ParentApprovalController@printBlankForms');
Route::get('pinnacle/review-attendend/{caseId}', 'ProcessRequestController@showReviewAttended');