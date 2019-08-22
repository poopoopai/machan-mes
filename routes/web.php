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


    Route::get('/resource', 'ResourceController@index')->name('resource');
    Route::get('machineperformance', 'ResourceController@show')->name('show_machine');
    Route::resource('machine-category', 'MachineCategoryController')->except('show');
    
    Route::post('/rest-time/{id}', 'RestTimeController@createData');
    Route::resource('rest-time', 'RestTimeController')->except('show');
    Route::put('/rest-time/{id}/setup/{setup_id}', 'RestTimeController@updateData')->name('update-data');
    Route::delete('/rest-time/{id}/setup/{setup_id}', 'RestTimeController@deleteData')->name('delete-data');
    Route::resource('work-type', 'WorkTypeController')->except('show');
    Route::resource('machine-definition', 'MachineDefinitionController');

    Route::resource('variable-formula', 'VariableFormulaController')->except('show');
    Route::resource('formula-setting', 'FormulaSettingController')->except('show');
    Route::resource('machineoee', 'MachineOeeController')->except('show');
    Route::resource('aps-processcode', 'ApsProcessCodeController')->except('show');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/inform', 'ResourceController@inform')->name('inform');
        

        
    
    Route::get('/process-routing', 'web\ProcessRoutingController@index')->name('process-routing');

    Route::get('/processing-time', 'web\ProcessingTimeController@index')->name('processing-time');
    Route::get('/processing-time-result', 'web\ProcessingTimeController@result')->name('processing-time-result');

    Route::get('/machine-performance', 'web\MachinePerformanceController@index')->name('machine-performance');
    Route::get('/order-load', 'web\OrderLoadController@index')->name('order-load');
    Route::get('/order-demand', 'web\OrderDemandController@index')->name('order-demand');
    Route::get('/order-inbound', 'web\OrderInboundController@index')->name('order-inbound');
    
    Route::get('/personnel-management', 'web\PersonnelManagementController@index')->name('personnel-management');

    Route::group(['prefix' => 'edit'], function () {
        
        Route::get('/process-routing', 'web\ProcessRoutingController@edit')->name('edit-process-routing');
        Route::get('/processing-time', 'web\ProcessingTimeController@edit')->name('edit-processing-time');
        Route::get('/personnel-management', 'web\PersonnelManagementController@edit')->name('edit-personnel-management');
    });

        Route::get('/full-calendar', 'CalendarController@fullcalendar')->name('full-calendar');
        Route::get('/year-calendar', 'CalendarController@yearcalendar')->name('year-calendar');
        Route::get('/process-calendar', 'ProcessCalendarController@processcalendar')->name('process-calendar');
        Route::get('/adjust-process-calendar', 'ProcessCalendarController@showProcessCalendar')->name('show-process-calendar');
        
});



