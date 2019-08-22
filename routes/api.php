<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/calendar-data', 'CalendarController@getCalnedar')->name('getcalendar');
Route::post('/calendar-data', 'CalendarController@calendar')->name('calendar-data');
Route::get('/change_resources', 'api\ResourceController@change_resources');
Route::get('/rest-data', 'RestTimeController@getRestTime')->name('rest-data');
Route::get('/work-type-data', 'WorkTypeController@getWorkTypeData')->name('work-type-data');
Route::get('/rest-group', 'WorkTypeController@getRestGroup')->name('rest-group');
Route::get('/machine-data', 'MachineCategoryController@getMachineId')->name('getMachineData');

Route::get('/getapsprocesscode', 'ApsProcessCodeController@getApsData')->name('getApsProcessCode');
Route::get('/getdatabase', 'ResourceController@getdatabase')->name('getdatabase');
Route::get('/getmachinedatabase', 'api\ResourceController@getmachinedatabase')->name('getmachinedatabase');
Route::get('/test', 'ResourceController@test');
Route::get('/test2', 'ResourceController@test2');