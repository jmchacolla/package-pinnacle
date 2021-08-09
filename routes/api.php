<?php
Route::group(['middleware' => ['auth:api', 'bindings']], function() {
    Route::get('admin/package-pinnacle/fetch', 'PackagePinnacleController@fetch')->name('package.skeleton.fetch');
    Route::apiResource('admin/package-pinnacle', 'PackagePinnacleController');
});
