<?php

Route::group(['middleware' => ['auth']], function () {
    Route::get('admin/package-pinnacle', 'PackagePinnacleController@index')->name('package.skeleton.index');
    Route::get('package-pinnacle', 'PackagePinnacleController@index')->name('package.skeleton.tab.index');
});
