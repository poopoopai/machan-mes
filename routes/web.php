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
Route::group(['middleware' => ['auth']], function () {

        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/work-type', 'WorkTypeController@index')->name('work-type');
        Route::get('/break-time', 'BreakTimeController@index')->name('break-time');
        Route::get('/machine-category', 'MachineCategoryController@index')->name('machine-category');
        Route::get('/machine-definition', 'MachineDefinitionController@index')->name('machine-definition');
        Route::get('/aps-processcode', 'ApsProcessCodeController@index')->name('aps-processcode');
        Route::get('/process-routing', 'ProcessRoutingController@index')->name('process-routing');
        Route::get('/exclusion-reason', 'ExclusionReasonController@index')->name('exclusion-reason');
        Route::get('/abnormal-reason', 'AbnormalReasonController@index')->name('abnormal-reason');
        Route::get('/processing-time', 'ProcessingTimeController@index')->name('processing-time');
        Route::get('/processing-time-result', 'ProcessingTimeController@result')->name('processing-time-result');

        

    Route::group(['prefix' => 'edit'], function () {
        Route::get('/work-type', 'WorkTypeController@edit')->name('edit-work-type');
        Route::get('/break-time', 'BreakTimeController@edit')->name('edit-break-time');
        Route::get('/machine-category', 'MachineCategoryController@edit')->name('edit-machine-category');
        Route::get('/machine-definition', 'MachineDefinitionController@edit')->name('edit-machine-definition');
        Route::get('/aps-processcode', 'ApsProcessCodeController@edit')->name('edit-aps-processcode'); //前url 後表單
        Route::get('/process-routing', 'ProcessRoutingController@edit')->name('edit-process-routing');
        Route::get('/exclusion-reason', 'ExclusionReasonController@edit')->name('edit-exclusion-reason');
        Route::get('/abnormal-reason', 'AbnormalReasonController@edit')->name('edit-abnormal-reason');
        Route::get('/processing-time', 'ProcessingTimeController@edit')->name('edit-processing-time');
        Route::get('/time-shift-definition', 'TimeShiftDefinitionController@edit')->name('edit-time-shift-definition');
        Route::get('/performance', 'PerformanceController@edit')->name('edit-performance');
        Route::get('/quality', 'QualityController@edit')->name('edit-quality');
        Route::get('/machine-oee', 'MachineOEEController@edit')->name('edit-machine-oee');
    });

    Route::group(['prefix' => 'calender'], function () {
        Route::get('/full-calendar', 'CalendarController@fullcalendar')->name('full-calendar');
        Route::get('/year-calendar', 'CalendarController@yearcalendar')->name('year-calendar');
        Route::get('/process-calendar', 'ProcessCalendarController@processcalendar')->name('process-calendar');
        Route::get('/adjust-process-calendar', 'ProcessCalendarController@showProcessCalendar')->name('show-process-calendar');
    });

    Route::group(['prefix' => 'uptime'], function () {
        Route::get('/time-shift-definition', 'TimeShiftDefinitionController@index')->name('time-shift-definition');
        Route::get('/performance', 'PerformanceController@index')->name('performance');
        Route::get('/quality', 'QualityController@index')->name('quality');
        Route::get('/machine-oee', 'MachineOEEController@index')->name('machine-oee');
    });
});



