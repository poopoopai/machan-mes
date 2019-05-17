<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', function () {
    return redirect('login');
});
Route::get('/test', 'ResourceController@test');
Route::group(['middleware' => ['auth']], function () {


    Route::get('/machine-definition', 'web\MachineDefinitionController@index')->name('machine-definition');

    Route::get('/resource', 'ResourceController@index')->name('resource');
    Route::resource('machine-category', 'MachineCategoryController');
    Route::get('machineperformance', 'ResourceController@show')->name('show_machine');












        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/work-type', 'web\WorkTypeController@index')->name('work-type');
        Route::get('/break-time', 'web\BreakTimeController@index')->name('break-time');
        
       
        Route::get('/aps-processcode', 'web\ApsProcessCodeController@index')->name('aps-processcode');
        Route::get('/process-routing', 'web\ProcessRoutingController@index')->name('process-routing');
        Route::get('/exclusion-reason', 'web\ExclusionReasonController@index')->name('exclusion-reason');
        Route::get('/abnormal-reason', 'web\AbnormalReasonController@index')->name('abnormal-reason');
        Route::get('/processing-time', 'web\ProcessingTimeController@index')->name('processing-time');
        Route::get('/processing-time-result', 'web\ProcessingTimeController@result')->name('processing-time-result');

        Route::get('/machine-performance', 'web\MachinePerformanceController@index')->name('machine-performance');
        Route::get('/order-load', 'web\OrderLoadController@index')->name('order-load');
        Route::get('/order-demand', 'web\OrderDemandController@index')->name('order-demand');
        Route::get('/order-inbound', 'web\OrderInboundController@index')->name('order-inbound');
        
        Route::get('/personnel-management', 'web\PersonnelManagementController@index')->name('personnel-management');

        
        

    Route::group(['prefix' => 'edit'], function () {
        Route::get('/work-type', 'web\WorkTypeController@edit')->name('edit-work-type');
        Route::get('/break-time', 'web\BreakTimeController@edit')->name('edit-break-time');
        Route::get('/machine-definition', 'web\MachineDefinitionController@edit')->name('edit-machine-definition');
        Route::get('/aps-processcode', 'web\ApsProcessCodeController@edit')->name('edit-aps-processcode'); //前url 後表單
        Route::get('/process-routing', 'web\ProcessRoutingController@edit')->name('edit-process-routing');
        Route::get('/exclusion-reason', 'web\ExclusionReasonController@edit')->name('edit-exclusion-reason');
        Route::get('/abnormal-reason', 'web\AbnormalReasonController@edit')->name('edit-abnormal-reason');
        Route::get('/processing-time', 'web\ProcessingTimeController@edit')->name('edit-processing-time');
        Route::get('/time-shift-definition', 'web\TimeShiftDefinitionController@edit')->name('edit-time-shift-definition');
        Route::get('/performance', 'web\PerformanceController@edit')->name('edit-performance');
        Route::get('/quality', 'web\QualityController@edit')->name('edit-quality');
        Route::get('/machine-oee', 'web\MachineOEEController@edit')->name('edit-machine-oee');
        Route::get('/personnel-management', 'web\PersonnelManagementController@edit')->name('edit-personnel-management');
    });

    Route::group(['prefix' => 'calendar'], function () {
        Route::get('/full-calendar', 'CalendarController@fullcalendar')->name('full-calendar');
        Route::get('/year-calendar', 'CalendarController@yearcalendar')->name('year-calendar');
        Route::get('/process-calendar', 'ProcessCalendarController@processcalendar')->name('process-calendar');
        Route::get('/adjust-process-calendar', 'ProcessCalendarController@showProcessCalendar')->name('show-process-calendar');
    });

    Route::group(['prefix' => 'uptime'], function () {
        Route::get('/time-shift-definition', 'web\TimeShiftDefinitionController@index')->name('time-shift-definition');
        Route::get('/performance', 'web\PerformanceController@index')->name('performance');
        Route::get('/quality', 'web\QualityController@index')->name('quality');
        Route::get('/machine-oee', 'web\MachineOEEController@index')->name('machine-oee');
    });


        
});



