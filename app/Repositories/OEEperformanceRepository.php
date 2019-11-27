<?php

namespace App\Repositories;

use App\Entities\Summary;
use App\Entities\Resource;
use App\Entities\StandardCt;
use App\Entities\ProcessCalendar;
use App\Entities\CompanyCalendar;
use App\Entities\SetupShift;
use App\Entities\RestSetup;
use App\Entities\DayPerformanceStatistics;
use Carbon\Carbon;

class OEEperformanceRepository
{
    public function work($sum){
        $work = [];
        
        $work['date'] = Carbon::today()->format("Y-m-d"); // date
        // $work['date'] = '2019-11-09';
        // dd($work['date']);

        $day = Carbon::now()->dayOfWeek; // day
        if($day == 1){
            $work['day'] = '一';
        }elseif($day == 2){
            $work['day'] = '二';
        }elseif($day == 3){
            $work['day'] = '三';
        }elseif($day == 4){
            $work['day'] = '四';
        }elseif($day == 5){
            $work['day'] = '五';
        }elseif($day == 6){
            $work['day'] = '六';
        }elseif($day == 7){
            $work['day'] = '日';
        }

        $weekend = Carbon::now()->isWeekend(); // weekend
        if($weekend == false){
            $work['weekend'] = '';
        }else{
            $work['weekend'] = '休';
        }
        
        // $work_type = ProcessCalendar::where('date', '2019-11-08')->first(); // work_name
        $com_work_type = CompanyCalendar::where('date', $work['date'])->first(); //看看公司行事曆有沒有加班資料

        if( $com_work_type == null ){ //如果公司行事曆沒資料
            $work_type = ProcessCalendar::where('date', $work['date'])->first(); //看看機台行事曆有沒有加班資料
            
            if( $work_type == false ){ //如果機台行事曆沒有加班資料 //沒加班
                $work['work_name'] = '正常班';  
            }elseif( $work_type->work_type_id == null ){
                if($work_type->status == 2 ){
                    $work['work_name'] = '休假';
                }elseif($work_type->status == 3){
                    $work['work_name'] = '國定假日';
                }
            }else{ //有加班
                $work_name = SetupShift::where('id', $work_type->work_type_id)->first()->type;
                if($work['weekend'] == '休'){
                    $work['work_name'] = '假日加班';
                }else{
                    $work['work_name'] = $work_name;
                }
            }

        }else{  //如果公司行事曆有資料 
            if( $com_work_type->work_type_id == null ){
                if($com_work_type->status == 2 ){
                    $work['work_name'] = '休假';
                }elseif($com_work_type->status == 3){
                    $work['work_name'] = '國定假日';
                }
            }else{ //有加班
                $work_name = SetupShift::where('id', $com_work_type->work_type_id)->first()->type;
                if($work['weekend'] == '休'){
                    $work['work_name'] = '假日加班';
                }else{
                    $work['work_name'] = $work_name;
                }
            }
        }


        // standard_working_hours
        if($work['work_name'] == '正常班'){
            $work['standard_working_hours'] = 8;
        }elseif($work['work_name'] == '休假' || $work['work_name'] == '國定假日'){
            $work['standard_working_hours'] = 0;
        }else{  // 以下處理加班
            if($com_work_type == null){  // 如果公司行事曆沒資料
                $work_type_id = ProcessCalendar::where('date', $work['date'])->first()->work_type_id; //看看機台行事曆的加班資料
                $work_time = SetupShift::where('id', $work_type_id)->first();
                $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
                $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());

                $rest = RestSetup::where('id', $work_time->rest_group)->first();
                $rest_start = strtotime($rest->start) - strtotime(Carbon::today());
                $rest_end = strtotime($rest->end) - strtotime(Carbon::today());
                $rest_time = $rest_end - $rest_start;

                $work['standard_working_hours'] = date("H:i:s", ($work_off - $work_on - $rest_time + 28800)-8*60*60); 
                // workoff - workon - 休息時間 + 8hour      28800為8小時的時間戳(秒數)
            }else{
                $work_time = SetupShift::where('id', $com_work_type->work_type_id)->first();
                $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
                $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());

                $rest = RestSetup::where('id', $work_time->rest_group)->first();
                $rest_start = strtotime($rest->start) - strtotime(Carbon::today());
                $rest_end = strtotime($rest->end) - strtotime(Carbon::today());
                $rest_time = $rest_end - $rest_start;

                $work['standard_working_hours'] = date("H:i:s", ($work_off - $work_on - $rest_time + 28800)-8*60*60); 
            }
        }


        // total_hours
        if($work['work_name'] == '正常班'){
            $work['total_hours'] = '9:20:00';
        }elseif($work['work_name'] == '休假' || $work['work_name'] == '國定假日'){
            $work['total_hours'] = '00:00:00';
        }else{  // 以下處理加班
            if($com_work_type == null){  // 如果公司行事曆沒資料
                $work_type_id = ProcessCalendar::where('date', $work['date'])->first()->work_type_id; //看看機台行事曆的加班資料
                $work_time = SetupShift::where('id', $work_type_id)->first();
                $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
                $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());

                $rest = RestSetup::where('id', $work_time->rest_group)->first();
                $rest_start = strtotime($rest->start) - strtotime(Carbon::today());
                $rest_end = strtotime($rest->end) - strtotime(Carbon::today());
                $rest_time = $rest_end - $rest_start;

                $work['total_hours'] = date("H:i:s", ($work_off - $work_on - $rest_time + 28800)-8*60*60); 
                // workoff - workon - 休息時間 + 8hour      28800為8小時的時間戳(秒數)
            }else{
                $work_time = SetupShift::where('id', $com_work_type->work_type_id)->first();
                $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
                $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());

                $rest = RestSetup::where('id', $work_time->rest_group)->first();
                $rest_start = strtotime($rest->start) - strtotime(Carbon::today());
                $rest_end = strtotime($rest->end) - strtotime(Carbon::today());
                $rest_time = $rest_end - $rest_start;

                $work['total_hours'] = date("H:i:s", ($work_off - $work_on - $rest_time + 28800)-8*60*60); 
            }
        }

        return $work;

    }


    //////////////////////////機台加工時間      machine_processing_time

    public function mass_production_time($sum){

        $max_serial_number_day = Summary::with('resource')->whereRaw('serial_number_day = (select max(`serial_number_day`) from summaries)')->first()->serial_number_day; 
        $get_serial_number_with_maxDay = Summary::where('serial_number',$max_serial_number_day)->get();

        $nextDay = date("Y-m-d",strtotime($sum['date']."+1 day"));
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $getNextDay = Resource::where('date', $nextDay)->with('summary')->get();
        $s = 0; $s1 = 0; $s2 = 0;

        if( $getSameDay->first() == null ){
            return '';
        }else{
            if( $getNextDay !== null ){
                foreach($get_serial_number_with_maxDay as $key =>$data){
                    $t = strtotime($data->time) - strtotime(Carbon::today());
                    $s1 = $s1 + $t;
                }
                foreach($getSameDay as $key =>$data){
                    if($data->serial_number_day == 1){
                        $t = strtotime($data->time) - strtotime(Carbon::today());
                        $s2 = $s2 + $t; 
                    }
                    
                }
                $s = $s1 - $s2;
                return date("H:i:s", $s-8*60*60);

            }else{
                foreach($getSameDay as $key =>$data){
                    if( $data->serial_number_day == $max_serial_number_day ){
                        $t = strtotime($data->time) - strtotime(Carbon::today());
                        $s1 = $s1 + $t;
                    }
                }
                foreach($getSameDay as $key =>$data){
                    if( $data->serial_number_day == 1 ){
                        $t = strtotime($data->time) - strtotime(Carbon::today());
                        $s2 = $s2 + $t;
                    }
                }
                $s = $s1 - $s2;
                return date("H:i:s", $s-8*60*60);
            }   
        }
    }
    public function machine_processing_time($sum){
        $machine_processing_time = [];

        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        
        $total_downtime = 0;   // total_downtime
        if( $getSameDay->first() == null ){ 
            $machine_processing_time['total_downtime'] = '';
        }else{
            foreach($getSameDay as $key =>$data){
                $total_downtime = $total_downtime + $data->down_time;
            }
            $machine_processing_time['total_downtime'] = $total_downtime;
        }

        $standard_processing_seconds = 0;
        foreach($getSameDay as $key =>$data){  // standard_processing_seconds
            $standard_processing_seconds = $standard_processing_seconds + $data->standard_uat_h_26_2 + $data->standard_uat_h_26_3 + $data->standard_uat_h_36_3;
        }
        $machine_processing_time['standard_processing_seconds'] = $standard_processing_seconds;


        $actual_processing_seconds = 0;
        foreach($getSameDay as $key =>$data){  // actual_processing_seconds
            $actual_processing_seconds = $actual_processing_seconds + $data->uat_h_26_2 + $data->uat_h_26_3 + $data->uat_h_36_3;
        }
        $machine_processing_time['actual_processing_seconds'] = $actual_processing_seconds;


        $machine_processing_time['updown_time'] =  0;  // updown_time

        return $machine_processing_time;
    }
    
    
    //////////////////////////////////機檯作業數量      machine_works_number

    public function machine_works_number($sum){
        $machine_works_number = [];

        $max_serial_number = Summary::with('resource')->whereRaw('serial_number = (select max(`serial_number`) from summaries)')->first()->serial_number; 
        $get_max_serial_number = Summary::where('serial_number',$max_serial_number)->get();
        
        $nextDay = date("Y-m-d",strtotime($sum['date']."+1 day"));
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $getNextDay = Resource::where('date', $nextDay)->with('summary')->get();
        $sameDay_status_id10 = Resource::where('date', $sum['date'])->where('status_id','10')->get();
        
        //  total_completion_that_day
        $max_machine_completion_day = Summary::with('resource')->whereRaw('machine_completion_day = (select max(`machine_completion_day`) from summaries)')->first()->machine_completion_day; 
        $total_completion_that_day = 0;

        if( $getSameDay->first() == null ){            
            $machine_works_number['total_completion_that_day'] = '';
        }else{
            if( $getNextDay->first() !== null ){
                $machine_works_number['total_completion_that_day'] = $max_machine_completion_day;
            }else{
                foreach($get_max_serial_number as $key =>$data){
                    $total_completion_that_day = $total_completion_that_day + $data->machine_completion_day;
                }
                $machine_works_number['total_completion_that_day'] = $total_completion_that_day;
            }   
        }

        
        //  machine_processing
        $max_machine_inputs_day = Summary::with('resource')->whereRaw('machine_inputs_day = (select max(`machine_inputs_day`) from summaries)')->first()->machine_inputs_day; 
        $machine_processing = 0;

        if( $getSameDay->first() == null ){
            $machine_works_number['machine_processing'] = '';
        }else{
            if( $getNextDay->first() !== null ){
                $machine_works_number['machine_processing'] = $max_machine_inputs_day;
            }else{
                foreach($get_max_serial_number as $key =>$data){
                    $machine_processing = $machine_processing + $data->machine_inputs_day;
                }
                $machine_works_number['machine_processing'] = $machine_processing;
            }   
        }


        //  actual_production_quantity
        $actual_production_quantity = 0;

        if( $getSameDay->first() == null ){
            $machine_works_number['actual_production_quantity'] = '';
        }else{
            foreach($sameDay_status_id10 as $key =>$data){
                $actual_production_quantity = $actual_production_quantity + 1;
            }
            $machine_works_number['actual_production_quantity'] = $actual_production_quantity;
        }


        //  standard_completion
        if( $getSameDay->first() == null ){
            $machine_works_number['standard_completion'] = 0;
        }else{
            if($sum['standard_processing_seconds'] == 0){
                $machine_works_number['standard_completion'] = 0;
            }else{
                $machine_works_number['standard_completion'] = ($sum['actual_processing_seconds']*$machine_works_number['total_completion_that_day']/$sum['standard_processing_seconds']);
            }
        }


        //  total_input_that_day
        $total_input_that_day = 0;

        if( $getSameDay->first() == null ){
            $machine_works_number['total_input_that_day'] = '';
        }else{
            foreach($sameDay_status_id10 as $key =>$data){
                $total_input_that_day = $total_input_that_day + 1;
            }
            $machine_works_number['total_input_that_day'] = $total_input_that_day;
        }


        //  adverse_number
        if($machine_works_number['total_completion_that_day'] == ''){
            $machine_works_number['adverse_number'] = '';
        }else{
            $machine_works_number['adverse_number'] = ($machine_works_number['total_input_that_day'] - $machine_works_number['total_completion_that_day']);
        }

        return $machine_works_number;
    }


    ///////////////////////////////////////機台嫁動除外工時   machinee_work_except_hours

    public function machinee_work_except_hours($sum){
        $machinee_work_except_hours = [];
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        
        $machinee_work_except_hours['correction_time'] = '';

        // hanging_time  
        // SUMIFS(捲料機績效分析!AN:AN, 捲料機績效分析!E:E,OEE績效數據!B6) AN = refueling_time
        $hanging_time = 0;
        foreach($getSameDay as $key =>$data){
            if($data->refueling_time != "00:00:00"){
                $refueling_time = strtotime($data->refueling_time) - strtotime(Carbon::today()); //將字串改為時間戳  之後再相減進行校正
                $hanging_time = $hanging_time + $refueling_time;
            }
        }
        $machinee_work_except_hours['hanging_time'] = date("H:i:s", $hanging_time-8*60*60);//將時間戳轉回字串


        // aggregate_time
        // SUMIFS(捲料機績效分析!AR:AR, 捲料機績效分析!E:E,OEE績效數據!B6) AR = aggregate_time
        $sum_aggregate_time = 0;
        foreach($getSameDay as $key =>$data){
            if($data->aggregate_time != "00:00:00"){
                $aggregate_time = strtotime($data->aggregate_time) - strtotime(Carbon::today()); //將字串改為時間戳  之後再相減進行校正
                $sum_aggregate_time = $sum_aggregate_time + $aggregate_time;
            }
        }
        $machinee_work_except_hours['aggregate_time'] = date("H:i:s", $sum_aggregate_time-8*60*60);//將時間戳轉回字串


        // break_time
        // SUMIFS(捲料機績效分析!U:U, 捲料機績效分析!E:E,OEE績效數據!B6) U = break_time
        $sum_break_time = 0;
        foreach($getSameDay as $key =>$data){
            if($data->break_time != "00:00:00"){
                $break_time = strtotime($data->break_time) - strtotime(Carbon::today()); //將字串改為時間戳  之後再相減進行校正
                $sum_break_time = $sum_break_time + $break_time;
            }
        }
        $machinee_work_except_hours['break_time'] = date("H:i:s", $sum_break_time-8*60*60);//將時間戳轉回字串


        $machinee_work_except_hours['chang_model_and_line'] = '';

        // machine_downtime
        // IF(O6="","", O6-SUM(T6:V6))
        $hanging_time = strtotime($machinee_work_except_hours['hanging_time']) - strtotime(Carbon::today());
        $aggregate_time = strtotime($machinee_work_except_hours['aggregate_time']) - strtotime(Carbon::today());
        $break_time = strtotime($machinee_work_except_hours['break_time']) - strtotime(Carbon::today());
        $machine_downtime = $hanging_time + $aggregate_time + $break_time;
        
        if($sum['total_downtime'] == '' || $sum['total_downtime'] == 0){
            $machinee_work_except_hours['machine_downtime'] = '';
        }else{
            $total_downtime = strtotime($sum['total_downtime']) - strtotime(Carbon::today());
            $machinee_work_except_hours['machine_downtime'] = date("H:i:s", ($total_downtime - $machine_downtime)-8*60*60);
        }

        $machinee_work_except_hours['bad_disposal_time'] = '';
        $machinee_work_except_hours['model_damge_change_line_time'] = '';
        $machinee_work_except_hours['program_modify_time'] = '';
        $machinee_work_except_hours['machine_maintain_time'] = '';

        // excluded_working_hours
        $excluded_working_hours = 0;
        $a0 = $sum['total_downtime'];
        $a1 = $sum['standard_processing_seconds'];
        $a2 = $sum['actual_processing_seconds'];
        $a3 = $sum['updown_time'];
        $a4 = $machinee_work_except_hours['correction_time'];
        $a5 = $machinee_work_except_hours['hanging_time'];
        $a6 = $machinee_work_except_hours['aggregate_time'];
        $a7 = $machinee_work_except_hours['break_time'];
        $a8 = $machinee_work_except_hours['chang_model_and_line'];
        $a9 = $machinee_work_except_hours['machine_downtime'];
        $a10 = $machinee_work_except_hours['bad_disposal_time'];
        $a11 = $machinee_work_except_hours['model_damge_change_line_time'];
        $a12 = $machinee_work_except_hours['program_modify_time'];
        $a13 = $machinee_work_except_hours['machine_maintain_time'];
        $a = array($a0, $a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9, $a10, $a11, $a12, $a13);

        for( $i=0 ; $i<14 ; $i++ ){     
            if($a[$i] == '' || $a[$i] == 0){      //把原本是空白格的時間更正為0
                $a[$i] = "00:00:00";
            } 
            $a[$i] = strtotime($a[$i]) - strtotime(Carbon::today());
            $excluded_working_hours = $excluded_working_hours + $a[$i];
        }
        $machinee_work_except_hours['excluded_working_hours'] = date("H:i:s", $excluded_working_hours-8*60*60);

        return $machinee_work_except_hours;

    }
    
    
    public function machine_performance($sum){

        $machine_performance = [];
        
        $sameday = DayPerformanceStatistics::where('report_work_date', $sum['date'])->get();
        $machine_utilization_rate = 0;
        foreach($sameday as $key =>$datas){
            $machine_utilization_rate = $machine_utilization_rate + $datas->machine_utilization_rate;
        }
        $machine_performance['machine_utilization_rate'] = $machine_utilization_rate/count($sameday);

        
        $performance_rate = 0;
        foreach($sameday as $key =>$datas){
            $performance_rate = $performance_rate + $datas->performance_rate;
        }
        $machine_performance['performance_rate'] = $performance_rate/count($sameday);


        
        $yield = 0;
        foreach($sameday as $key =>$datas){
            $yield = $yield + $datas->yield;
        }
        $machine_performance['yield'] = $yield/count($sameday);


        
        $OEE = 0;
        foreach($sameday as $key =>$datas){
            $OEE = $OEE + $datas->OEE;
        }
        $machine_performance['OEE'] = $OEE/count($sameday);

        return $machine_performance;
    }
}