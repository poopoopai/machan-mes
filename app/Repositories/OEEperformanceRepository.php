<?php

namespace App\Repositories;

use App\Entities\Summary;
use App\Entities\Resource;
use App\Entities\StandardCt;
use App\Entities\ProcessCalendar;
use App\Entities\SetupShift;
use Carbon\Carbon;

class OEEperformanceRepository
{
    public function work($sum){
        $work = [];
        
        $work['date'] = Carbon::today()->format("Y-m-d"); // date

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

        $work_type = ProcessCalendar::where('date', '2019-01-01')->first(); // work_name
        // $work_type = ProcessCalendar::where('date', $work['date'])->first();
        if($work_type == false){ //如果沒有加班資料
            $work['work_name'] = '';
        }
        if($work_type->work_type_id == null){
            if($work_type->status == 2){
                $work['work_name'] = '休假';
            }elseif($work_type->status == 3){
                $work['work_name'] = '國定假日';
            }
        }
        $work_name = SetupShift::where('id', $work_type->work_type_id)->first()->type;
        $work['work_name'] = $work_name;

        // standard_working_hours
        if($work['work_name'] == ''){
            return 8;
        }elseif($work['work_name'] == '休假' || $work['work_name'] == '國定假日'){
            return 0;
        }else{
            $work_type_id = ProcessCalendar::where('date', '2019-01-01')->first()->work_type_id;//抓今天的加班id時段
            // $work_type_id = ProcessCalendar::where('date', $work['date'])->first()->work_type_id;
            $work_time = SetupShift::where('id', $work_type_id)->first();
            $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
            $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());
            $work['standard_working_hours'] = date("H:i:s", ($work_off - $work_on + 28800)-8*60*60); 
            // workoff - workon + 8hour  28800為8小時的時間戳
        }

        // total_hours
        if($work['work_name'] == ''){
            return '9:20:00';
        }elseif($work['work_name'] == '休假' || $work['work_name'] == '國定假日'){
            return '00:00:00';
        }else{
            $work_type_id = ProcessCalendar::where('date', '2019-01-01')->first()->work_type_id;//抓今天的加班id時段
            // $work_type_id = ProcessCalendar::where('date', $work['date'])->first()->work_type_id;
            $work_time = SetupShift::where('id', $work_type_id)->first();
            $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
            $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());
            $work['total_hours'] = date("H:i:s", ($work_off - $work_on + 28800)-8*60*60); //28800為8小時的時間戳
            //workoff - workon + 8
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

        if( $getSameDay == null ){
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
        if( $getSameDay == null ){ 
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


        $machine_processing_time['updown_time'] =  '';  // updown_time

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
            $machine_works_number['standard_completion'] = '';
        }else{
            if($sum['standard_processing_seconds'] == 0){
                $machine_works_number['standard_completion'] = '';
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

    // public function machine_processing($sum){

    //     $max_machine_inputs_day = Summary::with('resource')->whereRaw('machine_inputs_day = (select max(`machine_inputs_day`) from summaries)')->first()->machine_inputs_day; 
    //     $max_serial_number = Summary::with('resource')->whereRaw('serial_number = (select max(`serial_number`) from summaries)')->first()->serial_number; 
    //     $get_max_serial_number = Summary::where('serial_number',$max_serial_number)->get(); 

    //     $nextDay = date("Y-m-d",strtotime($sum['date']."+1 day"));
    //     $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
    //     $getNextDay = Resource::where('date', $nextDay)->with('summary')->get();
    //     $total = 0;

    //     if( $getSameDay == null ){
    //         return '';
    //     }else{
    //         if( $getNextDay !== null ){
    //             return $max_machine_inputs_day;
    //         }else{
    //             foreach($get_max_serial_number as $key =>$data){
    //                 $total = $total + $data->machine_inputs_day;
    //             }
    //             return $total;
    //         }   
    //     }
    // }
    // public function actual_production_quantity($sum){

    //     $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
    //     $sameDay_status_id10 = Resource::where('date', $sum['date'])->where('status_id','10')->get();
    //     $count = 0;
    //     if( $getSameDay == null ){
    //         return '';
    //     }else{
    //         foreach($sameDay_status_id10 as $key =>$data){
    //             $count = $count + 1;
    //         }
    //         return $count;
    //     }
    // }
    // public function standard_completion($sum){

    //     $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        
    //     if( $getSameDay == null ){
    //         return '';
    //     }else{
    //         if($sum['standard_processing_seconds'] == 0){
    //             return '';
    //         }
    //         return ($sum['actual_processing_seconds']*$sum['total_completion_that_day']/$sum['standard_processing_seconds']);
    //     }
    // }
    // public function total_input_that_day($sum){

    //     $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
    //     $sameDay_status_id10 = Resource::where('date', $sum['date'])->where('status_id','10')->get();
    //     $count = 0;
    //     if( $getSameDay == null ){
    //         return '';
    //     }else{
    //         foreach($sameDay_status_id10 as $key =>$data){
    //             $count = $count + 1;
    //         }
    //         return $count;
    //     }
    // }
    public function total_completion_that_day($sum){

        $max_machine_completion_day = Summary::with('resource')->whereRaw('machine_completion_day = (select max(`machine_completion_day`) from summaries)')->first()->machine_completion_day; 
        $max_serial_number = Summary::with('resource')->whereRaw('serial_number = (select max(`serial_number`) from summaries)')->first()->serial_number; 
        $get_max_serial_number = Summary::where('serial_number',$max_serial_number)->get();

        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $nextDay = date("Y-m-d",strtotime($sum['date']."+1 day"));
        $getNextDay = Resource::where('date', $nextDay)->with('summary')->get();
        $total = 0;

        if( $getSameDay == null ){
            return '';
        }else{
            if( $getNextDay !== null ){
                return $max_machine_completion_day;
            }else{
                foreach($get_max_serial_number as $key =>$data){
                    $total = $total + $data->machine_completion_day;
                }
                return $total;
            }   
        }
    }
    public function adverse_number($sum){
        if($sum['total_completion_that_day'] == ''){
            return '';
        }else{
            return ($sum['total_input_that_day'] - $sum['total_completion_that_day']);
        }
    }



    ///////////////////////////////////////機台嫁動除外工時   machinee_work_except_hours
    public function hanging_time($sum){
        // SUMIFS(捲料機績效分析!AN:AN, 捲料機績效分析!E:E,OEE績效數據!B6) AN = refueling_time
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $s = 0;

        foreach($getSameDay as $key =>$data){
            if($data->refueling_time != "00:00:00"){
                $abc = strtotime($data->refueling_time) - strtotime(Carbon::today()); //將字串改為時間戳  之後再相減進行校正
                $s = $s + $abc;
            }
        }
        return date("H:i:s", $s-8*60*60);//將時間戳轉回字串
    }
    public function aggregate_time($sum){
        // SUMIFS(捲料機績效分析!AR:AR, 捲料機績效分析!E:E,OEE績效數據!B6) AR = aggregate_time
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $s = 0;

        foreach($getSameDay as $key =>$data){
            if($data->aggregate_time != "00:00:00"){
                $abc = strtotime($data->aggregate_time) - strtotime(Carbon::today()); //將字串改為時間戳  之後再相減進行校正
                $s = $s + $abc;
            }
        }
        return date("H:i:s", $s-8*60*60);//將時間戳轉回字串
    }
    public function break_time($sum){
        // SUMIFS(捲料機績效分析!U:U, 捲料機績效分析!E:E,OEE績效數據!B6) U = break_time
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $s = 0;

        foreach($getSameDay as $key =>$data){
            if($data->break_time != "00:00:00"){
                $abc = strtotime($data->break_time) - strtotime(Carbon::today()); //將字串改為時間戳  之後再相減進行校正
                $s = $s + $abc;
            }
        }
        return date("H:i:s", $s-8*60*60);//將時間戳轉回字串
    }
    public function machine_downtime($sum){
        // IF(O6="","", O6-SUM(T6:V6))
        $hanging_time = strtotime($sum['hanging_time']) - strtotime(Carbon::today());
        $aggregate_time = strtotime($sum['aggregate_time']) - strtotime(Carbon::today());
        $break_time = strtotime($sum['break_time']) - strtotime(Carbon::today());
        $machine_downtime = $hanging_time + $aggregate_time + $break_time;
        
        if($sum['total_downtime'] == '' || $sum['total_downtime'] == 0){
            return '';
        }else{
            $total_downtime = strtotime($sum['total_downtime']) - strtotime(Carbon::today());
            return date("H:i:s", ($total_downtime - $machine_downtime)-8*60*60);//將時間戳轉回字串
        }
    }
    public function excluded_working_hours($sum){
        $excluded_working_hours = 0;
        $a0 = strtotime($sum['total_downtime']) - strtotime(Carbon::today());
        $a1 = strtotime($sum['standard_processing_seconds']) - strtotime(Carbon::today());
        $a2 = strtotime($sum['actual_processing_seconds']) - strtotime(Carbon::today());
        $a3 = strtotime($sum['updown_time']) - strtotime(Carbon::today());
        $a4 = strtotime($sum['correction_time']) - strtotime(Carbon::today());
        $a5 = strtotime($sum['hanging_time']) - strtotime(Carbon::today());
        $a6 = strtotime($sum['aggregate_time']) - strtotime(Carbon::today());
        $a7 = strtotime($sum['break_time']) - strtotime(Carbon::today());
        $a8 = strtotime($sum['chang_model_and_line']) - strtotime(Carbon::today());
        $a9 = strtotime($sum['machine_downtime']) - strtotime(Carbon::today());
        $a10 = strtotime($sum['bad_disposal_time']) - strtotime(Carbon::today());
        $a11 = strtotime($sum['model_damge_change_line_time']) - strtotime(Carbon::today());
        $a12 = strtotime($sum['program_modify_time']) - strtotime(Carbon::today());
        $a13 = strtotime($sum['machine_maintain_time']) - strtotime(Carbon::today());
        $a = array($a0, $a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9, $a10, $a11, $a12, $a13);

        for( $i=0 ; $i<14 ; $i++ ){     
            if($a[$i] !== -1568908800){      //如果不是空白格就累加
                $excluded_working_hours = $excluded_working_hours + $a[$i];
            }
        }
        return date("H:i:s", $excluded_working_hours-8*60*60);
    }

    public function machine_utilization_rate($sum){
        // IF(COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6)=0,"",(N6-O6+R6)/N6) 
        // N = mass_production_time, O = total_downtime, R = updown_time
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $s = 0; $mass_production_time = 0; $total_downtime = 0; $updown_time = 0;
        
        $mass_production_time = strtotime($sum['mass_production_time']) - strtotime(Carbon::today());
        $total_downtime = strtotime($sum['total_downtime']) - strtotime(Carbon::today());
        $updown_time = strtotime($sum['updown_time']) - strtotime(Carbon::today());
        
        if( $getSameDay == null ){
            return '';
        }else{
            if($sum['mass_production_time'] == ''){
                return '';
            }
            return (($mass_production_time - $total_downtime + $updown_time)/$mass_production_time);
        }
    }
    public function performance_rate($sum){
        // (COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6)=0,"", L6/J6 ) 
        // L = total_completion_that_day, J = standard_completion
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get(); 
        if( $getSameDay == null ){
            return '';
        }else{
            if($sum['standard_completion'] == ''){
                return '';
            }
            return ($sum['total_completion_that_day']/$sum['standard_completion']);
        }
    }
    public function yield($sum){
        // IF(COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6)=0,"",(L6-M6)/L6) 
        // L = total_completion_that_day, M = adverse_number
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        if( $getSameDay == null ){
            return '';
        }else{
            if($sum['total_completion_that_day'] == ''){
                return '';
            }
            return (($sum['total_completion_that_day']-$sum['adverse_number'])/$sum['total_completion_that_day']);
        }
    }
    public function OEE($sum){
        // IF(COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6)=0,"",AD6*AE6*AF6)
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        if( $getSameDay == null ){
            return '';
        }else{ //目前performance_rate為空值
            if($sum['machine_utilization_rate'] == '' || $sum['performance_rate'] == '' || $sum['yield'] == ''){
                return 0;
            }
            return ($sum['machine_utilization_rate']*$sum['performance_rate']*$sum['yield']);
        }
    }
}