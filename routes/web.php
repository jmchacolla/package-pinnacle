<?php

Route::group(['middleware' => ['auth']], function () {
    Route::get('pinnacle/request', 'ProcessRequestController@showProcessRequestList')->name('package.pinnacle.tab.pinnacle-report');
});
