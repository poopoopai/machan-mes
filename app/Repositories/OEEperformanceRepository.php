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
    public function day($sum){
        $day = Carbon::now()->dayOfWeek;
        if($day == 1){
            return '一';
        }elseif($day == 2){
            return '二';
        }elseif($day == 3){
            return '三';
        }elseif($day == 4){
            return '四';
        }elseif($day == 5){
            return '五';
        }elseif($day == 6){
            return '六';
        }elseif($day == 7){
            return '日';
        }
    }
    public function weekend($sum){
        $weekend = Carbon::now()->isWeekend();
        if($weekend == false){
            return '';
        }else{
            return '休';
        }   
    }
    public function work_name($sum){
        $work_type = ProcessCalendar::where('date', '2019-01-01')->first();
        // $work_type = ProcessCalendar::where('date', $sum['date'])->first();
        if($work_type == false){ //如果沒有加班資料
            return '';
        }
        if($work_type->work_type_id == null){
            if($work_type->status == 2){
                return '休假';
            }elseif($work_type->status == 3){
                return '國定假日';
            }
        }
        $work_name = SetupShift::where('id', $work_type->work_type_id)->first()->type;
        return $work_name;
    }
    public function standard_working_hours($sum){
        if($sum['work_name'] == ''){
            return 8;
        }elseif($sum['work_name'] == '休假' || $sum['work_name'] == '國定假日'){
            return 0;
        }else{
            $work_type_id = ProcessCalendar::where('date', '2019-01-01')->first()->work_type_id;//抓今天的加班id時段
            $work_time = SetupShift::where('id', $work_type_id)->first();
            $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
            $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());
            return date("H:i:s", ($work_off - $work_on + 28800)-8*60*60); //28800為8小時的時間戳
            //workoff - workon + 8
        }
    }
    public function total_hours($sum){
        if($sum['work_name'] == ''){
            return '9:20:00';
        }elseif($sum['work_name'] == '休假' || $sum['work_name'] == '國定假日'){
            return '00:00:00';
        }else{
            $work_type_id = ProcessCalendar::where('date', '2019-01-01')->first()->work_type_id;//抓今天的加班id時段
            $work_time = SetupShift::where('id', $work_type_id)->first();
            $work_off = strtotime($work_time->work_off) - strtotime(Carbon::today());
            $work_on = strtotime($work_time->work_on) - strtotime(Carbon::today());
            return date("H:i:s", ($work_off - $work_on + 28800)-8*60*60); //28800為8小時的時間戳
            //workoff - workon + 8
        }
    }
    //////////////////////////////////機檯作業數量      machine_works_number

    public function machine_processing($sum){
        // IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6) = 0, //同一天的資料數為0
        //  "",
        //  IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6+1) > 0 ,//明天的資料數 > 0
        //     MAX(捲料機績效分析!R:R), //R = machine_inputs_day
        //     SUMIFS(捲料機績效分析!R:R , 捲料機績效分析!J:J ,MAX(捲料機績效分析!J:J) ) // J = serial_number(要最大值)
        //     )
        // )
        $max_machine_inputs_day = Summary::with('resource')->whereRaw('machine_inputs_day = (select max(`machine_inputs_day`) from summaries)')->first()->machine_inputs_day; 
        $max_serial_number = Summary::with('resource')->whereRaw('serial_number = (select max(`serial_number`) from summaries)')->first()->serial_number; 
        $get_max_serial_number = Summary::where('serial_number',$max_serial_number)->get(); 

        $nextDay = date("Y-m-d",strtotime($sum['date']."+1 day"));
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $getNextDay = Resource::where('date', $nextDay)->with('summary')->get();
        $total = 0;

        if( $getSameDay == null ){
            return '';
        }else{
            if( $getNextDay !== null ){
                return $max_machine_inputs_day;
            }else{
                foreach($get_max_serial_number as $key =>$data){
                    $total = $total + $data->machine_inputs_day;
                }
                return $total;
            }   
        }
    }
    public function actual_production_quantity($sum){
        // IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6) = 0, //同一天的資料數為0
        // "",
        // COUNTIFS(捲料機績效分析!$C:$C,10,捲料機績效分析!$E:$E,B6))
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $sameDay_status_id10 = Resource::where('date', $sum['date'])->where('status_id','10')->get();
        $count = 0;
        if( $getSameDay == null ){
            return '';
        }else{
            foreach($sameDay_status_id10 as $key =>$data){
                $count = $count + 1;
            }
            return $count;
        }
    }
    public function standard_completion($sum){
        // IF(COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6)=0,"",Q6*L6/P6) ,L = total_completion_that_day,
        // P = standard_processing_seconds , Q = actual_processing_seconds
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        
        if( $getSameDay == null ){
            return '';
        }else{
            if($sum['standard_processing_seconds'] == 0){
                return '';
            }
            return ($sum['actual_processing_seconds']*$sum['total_completion_that_day']/$sum['standard_processing_seconds']);
        }
    }
    public function total_input_that_day($sum){
        // IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6)=0, //同一天的資料數為0
        // "",
        // COUNTIFS(捲料機績效分析!$C:$C,10,捲料機績效分析!$E:$E,$B6))
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $sameDay_status_id10 = Resource::where('date', $sum['date'])->where('status_id','10')->get();
        $count = 0;
        if( $getSameDay == null ){
            return '';
        }else{
            foreach($sameDay_status_id10 as $key =>$data){
                $count = $count + 1;
            }
            return $count;
        }
    }
    public function total_completion_that_day($sum){
        // IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B9)=0, //同一天的資料數為0
        //     "",
        //     IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B9+1) > 0, //明天的資料數 > 0
        //         MAX(捲料機績效分析!Q:Q),  //Q = machine_completion_day
        //         SUMIFS(捲料機績效分析!Q:Q,捲料機績效分析!J:J,MAX(捲料機績效分析!J:J))  // J = serial_number(要最大值)
        //     )
        // )
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
        //IF(L6="","",K6-L6)
        if($sum['total_completion_that_day'] == ''){
            return '';
        }else{
            return ($sum['total_input_that_day'] - $sum['total_completion_that_day']);
        }
    }

    //////////////////////////機台加工時間      machine_processing_time

    public function mass_production_time($sum){
        // IF( COUNTIFS( 捲料機績效分析!E:E,OEE績效數據!B6)=0,  //同一天資料量為0
        //     "",
        //     IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6+1)>0,  //有明天的資料     N = time
        //         SUMIFS(捲料機績效分析!N:N, 捲料機績效分析!J:J, MAX(捲料機績效分析!K:K))  J = serial_number, K = serial_number_day 
        //         - SUMIFS(捲料機績效分析!N:N, 捲料機績效分析!E:E, OEE績效數據!B6, 捲料機績效分析!K:K,1),

        //         SUMIFS(捲料機績效分析!N:N, 捲料機績效分析!E:E, OEE績效數據!B6, 捲料機績效分析!K:K, MAX(捲料機績效分析!K:K))
        //         - SUMIFS(捲料機績效分析!N:N, 捲料機績效分析!E:E, OEE績效數據!B6,捲料機績效分析!E:E,OEE績效數據!B6,捲料機績效分析!K:K,1)
        //     )
        // )
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
    public function total_downtime($sum){
        // IF( COUNTIFS(捲料機績效分析!E:E,OEE績效數據!B6)=0,
        //     "",
        //     SUMIFS(捲料機績效分析!W:W,捲料機績效分析!E:E,OEE績效數據!B6)      W = down_time
        // )
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $s = 0;

        if( $getSameDay == null ){
            return '';
        }else{
            foreach($getSameDay as $key =>$data){
                $s = $s + $data->down_time;
            }
            return $s;
        }
    }
    public function standard_processing_seconds($sum){
        // SUMIFS(捲料機績效分析!AW:AW, 捲料機績效分析!E:E, OEE績效數據!B6) AW = standard_uat_h_26_2
        // + SUMIFS(捲料機績效分析!AX:AX, 捲料機績效分析!E:E, OEE績效數據!B6) AX = standard_uat_h_26_3
        // + SUMIFS(捲料機績效分析!AY:AY, 捲料機績效分析!E:E, OEE績效數據!B6) AY = standard_uat_h_36_3
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $s = 0; $s1 = 0; $s2 =0; $s3 = 0;
        foreach($getSameDay as $key =>$data){
            $s1 = $s1 + $data->standard_uat_h_26_2;
            $s2 = $s2 + $data->standard_uat_h_26_3;
            $s3 = $s3 + $data->standard_uat_h_36_3;
        }
        $s = $s1 + $s2 + $s3;
        return $s;
    }
    public function actual_processing_seconds($sum){
        // SUMIFS(捲料機績效分析!AT:AT, 捲料機績效分析!E:E, OEE績效數據!B6) AT = uat_h_26_2
        // + SUMIFS(捲料機績效分析!AU:AU, 捲料機績效分析!E:E, OEE績效數據!B6) AU = uat_h_26_3
        // + SUMIFS(捲料機績效分析!AV:AV, 捲料機績效分析!E:E, OEE績效數據!B6) AV = uat_h_36_3
        $getSameDay = Resource::where('date', $sum['date'])->with('summary')->get();
        $s = 0; $s1 = 0; $s2 =0; $s3 = 0;
        foreach($getSameDay as $key =>$data){
            $s1 = $s1 + $data->uat_h_26_2;
            $s2 = $s2 + $data->uat_h_26_3;
            $s3 = $s3 + $data->uat_h_36_3;
        }
        $s = $s1 + $s2 + $s3;
        return $s;
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