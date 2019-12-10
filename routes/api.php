<?php
use Illuminate\Support\Facades\Route;
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
Route::get('/getMachineDefinition', 'MachineDefinitionController@getMachineDefinition')->name('getMachineDefinition');

Route::get('/getapsdata', 'ProcessRoutingController@getApsData')->name('getApsData');
Route::get('/getdatabase', 'ResourceController@getdatabase')->name('getdatabase');
Route::get('/getmachinedatabase', 'api\ResourceController@getmachinedatabase')->name('getmachinedatabase');
Route::get('/fixmachinedatabase', 'api\ResourceController@fixmachinedatabase')->name('fixmachinedatabase');
Route::get('/test', 'ResourceController@test');
Route::get('/test2', 'ResourceController@test2');
Route::get('/inform', 'ResourceController@inform');


Route::get('/organization', 'OrganizationController@getOrganization')->name('getorganization');
Route::get('/sale-order-data', 'SaleOrderController@getSaleOrderData')->name('save-sale-order');
Route::get('/current-load-data', 'ManufactureController@getCurrentLoadedData')->name('current-data');
Route::get('/sync-order-result-form', 'SaleOrderController@getSynchroizedResult')->name('sync-order-result-form');
Route::get('/get-bom-data', 'BomController@getBomData')->name('get-bom-data');

Route::resource('dayPerformanceStatistics', 'DayPerformanceStatisticsController');
Route::get('/day', 'DayPerformanceStatisticsController@getmachineperformance');
Route::get('/OEE', 'OEEperformanceController@getOEEperformance');

Route::get('/Processing-Time', 'ProcessingTimeController@ProcessingTimeIndex')->name('ProcessingTimeIndex');
Route::get('/Machine-Definition', 'MachineDefinitionController@machineDefinitionIndex')->name('MachineDefinitionIndex');
Route::get('/Machine-Category', 'MachineCategoryController@machineCategoryIndex')->name('MachineCategoryIndex');
Route::get('/get-work-time', 'WorkTypeController@getWokrTime')->name('get-work-time');
Route::get('/Organization', 'ProcessRoutingController@getOrganization')->name('getOrganization');

Route::get('/process-data', 'ProcessCalendarController@processCalendarData')->name('getprocesscalendar');
Route::get('/process-calendar-data', 'ProcessCalendarController@adjustProcessCalendar')->name('adjust-process-calendar');
Route::post('/process-calendar-data', 'ProcessCalendarController@workCalendar')->name('process-calendar-data');
Route::get('/work-data', 'ProcessCalendarController@workData')->name('work-data');

