<?php

namespace App\Repositories;

use App\Entities\Summary;
use App\Entities\Resource;
use App\Entities\StandardCt;
use Carbon\Carbon;
class SummaryRepository
{
    public function counts($data, $machine)
    {
        $count = Summary::with('resource')->whereRaw('id = (select max(`id`) from summaries)')->first(); //前一筆資料
       
        if($count == null){
            $count = Summary::create(['resources_id' => 0, 'description' => '', 'processing_start_time'=>'00:00:00', 'processing_completion_time'=>'00:00:00']);
        }
        
        $count->id = $count->id + 1;
        $count->time = $data->time;
        
        $oldopen = Summary::where('open', '!=', '')->orderby('id', 'desc')->first();
        $oldturn = Summary::where('turn_off', '!=', '')->orderby('id', 'desc')->first();
        if( $count->resources_id == 0 || $data['date'] != $count->resource->date){
            $count->open = 0;
            $count->turn_off = 0;
            $count->start_count = 0;
            $count->stop_count = 0;
            $data['status_id'] == 3 ? $count->open++ : $count->open = '';
            $data['status_id'] == 4 ? $count->turn_off++ : $count->turn_off = '';
            $data['status_id'] == 3 ? $count->start_count++ : $count->start_count;
            $data['status_id'] == 4 ? $count->stop_count++ : $count->stop_count;  
        } else{       
            $data['status_id'] == 3 ? $count->open = $oldopen->open + 1 : $count->open = '';
            $data['status_id'] == 4 ? $count->turn_off = $oldturn->turn_off + 1 : $count->turn_off = '';
            $data['status_id'] == 3 ? $count->start_count++ : $count->start_count;
            $data['status_id'] == 4 ? $count->stop_count++ : $count->stop_count; 
        }
        
            $data['status_id'] == 15 ? $count->sensro_inputs++ : $count->sensro_inputs;
            
        if($machine == '捲料機1'){
            $data['status_id'] == 9 ? $count->second_completion++ : $count->second_completion;
        } else{
            $data['status_id'] == 10 ? $count->second_completion++ : $count->second_completion;
        }
        
        $count->serial_number++;

        if( $count->resources_id == 0 || $data['orderno'] != $count->resource->orderno  ) { //料號不相同 $count->resources_id第一筆null
            $count->machine_completion = 0;
            $count->machine_inputs = 0;
                if ($machine == '捲料機1'){
                    $data['status_id'] == 9 ? $count->machine_completion++ : $count->machine_completion;
                } else{
                    $data['status_id'] == 10 ? $count->machine_completion++ : $count->machine_completion;
                }
                $data['status_id'] == 10 ? $count->machine_inputs++ : $count->machine_inputs;
        } else{

            $data['status_id'] == 10 ? $count->machine_inputs++ : $count->machine_inputs;
            
            if ($machine == '捲料機1'){
                $data['status_id'] == 9 ? $count->machine_completion++ : $count->machine_completion;
                $data['status_id'] == 9 ? $count->processing_completion_time = $data['time'] : $count->processing_completion_time = "";
            } else{
                $data['status_id'] == 10 ? $count->machine_completion++ : $count->machine_completion;
                $data['status_id'] == 10 ? $count->processing_completion_time = $data['time'] : $count->processing_completion_time = "";
            } 

            if($machine == '捲料機1'){
                $data['status_id'] == 10 ? $count->processing_start_time = $data['time'] : $count->processing_start_time = "00:00:00";
            } else{
                if($data['status_id'] == 10){
                    $count->processing_start_time = $data['time'];
                } else{
                    $completion = Summary::where('machine_completion_day', $count->machine_completion_day-1)->first();
                    $restart = Summary::where('start_count', $count->start_count)->first();//建一個累加開或關的
                    if($completion){
                        if($completion->machine_completion_day > 0){
                            $count->processing_start_time = $completion->time;
                        } else{
                            if($restart){
                                $count->processing_start_time = $restart->time;
                            }     
                        }
                    }
                }             
            }                
        }

            if( $count->resources_id == 0 || $data['date'] != $count->resource->date) { //累積當天數量
                $count->machine_completion_day = 0;
                $count->machine_inputs_day = 0;
                    if ($machine == '捲料機1'){
                        $data['status_id'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
                    } else{
                        $data['status_id'] == 10 ? $count->machine_completion_day++ : $count->machine_completion_day;
                    }
                        $data['status_id'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
            } else{
                if ($machine == '捲料機1'){
                    $data['status_id'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
                } else{
                    $data['status_id'] == 10 ? $count->machine_completion_day++ : $count->machine_completion_day;
                }
                $data['status_id'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
                $data['status_id'] == 20 ? $count->refueling_start++ : $count->refueling_start =0;
                $data['status_id'] == 21 ? $count->refueling_end++: $count->refueling_end = 0;
                $data['status_id'] == 22 ? $count->aggregate_start++: $count->aggregate_start = 0;
                $data['status_id'] == 23 ? $count->aggregate_end++: $count->aggregate_end = 0;
            }            
                
            if ( ($data['orderno'] == '' && $data['date'] != $count->resource->date) || $count->resources_id == 0  ){ //最初$count->resources_id
                $count->serial_number_day = 1 ;
            } else {
                if($data['orderno'] == '' && $data['date'] == $count->resource->date){
                    if($count){
                        $count->serial_number_day++;
                    } else{
                        $count->serial_number_day++;//最開始的那筆沒有資料
                    }
                } else{  //前面加總料號數量+1 且同料號同日期
                    if($count){
                        $count->serial_number_day++;
                    } else{
                        $count->serial_number_day++ ;
                    }       
                }
            }
        return $count;
    }

    public function restart($data, $status)
    {   
    
        if($status->open != ''){
            $restart = Summary::where('open', $status['open']-1)->first(); //上一筆開機關機
            if($status['open'] == ''){
                $status["restart_count"] = '';
            } else{
                if($status['open'] == '1'){
                    $status["restart_count"] = '' ;
                } else{
                    if($restart->open == $status['open'] && $date['date'] ){
                        $status["restart_count"] = ++$restart->restart_count;
                    } else{
                        $status["restart_count"] = '' ;
                    }
                }
            }
        } else{
            $status["restart_count"] = "";
        }

        if($status['turn_off'] != ''){

        $restop = Summary::where('turn_off', $status['turn_off']-1)->first();
       
            if($status['turn_off'] == ''){
                $status['restop_count'] = '';
            } else{
                if($status['turn_off'] == '1'){
                    $status['restop_count'] = '';
            } else{
                if($restop->turn_off ==  $status['turn_off'] && $date['date']){
                    $status['restop_count'] = ++$restop->restop_count;
                } else{
                    $status['restop_count'] = '';
                }
            }
        }
    } else{
        $status['restop_count'] = "";
        }
        return $status;
    }

    public function create($data)
    {   
       return Summary::create($data);
    }

    public function machineT($data, $status, $machine)
    {
        
        $machinetime = Summary::where('machine_inputs_day', $status['machine_inputs_day']-1)->where('resources_id', '>', 0)->first();     
        $completionday = Summary::where('machine_completion_day', $status['machine_inputs_day'])->where('resources_id', '>', 0)->first();
        
        $machineT = 0;
        $secondT = 0;
        
        if($machine == '捲料機1'){
            if ($data['status_id'] == 10) {
                if($status->machine_inputs_day >= 2){
                    if($machinetime->processing_start_time){
                        $machineT = strtotime($status->processing_start_time) - strtotime($machinetime->processing_start_time);
                    } else{
                        $machineT = 0;
                    }
                } 
                else{
                    $machineT = 0;
                }     
            } else{
                $machineT = 0;
            }
        } else{
            if ($data['status_id'] == 10){
                $machineT = strtotime($status->processing_completion_time) - strtotime($status->processing_start_time);
            } else{
                $machineT = 0;
            }
        }
        
        $machintime = Summary::where('machine_completion', $status['machine_completion'])->where('resources_id', '>', 0)->first();
        $machinetime2 = Summary::where('machine_completion', $status['machine_completion']-1)->where('resources_id', '>', 0)->first();//前
        $secondtime = Summary::where('machine_completion_day', $status['machine_completion_day']-1)->where('resources_id', '>', 0)->first();

        if($data['status_id'] == 9){
            
            if($status->machine_inputs_day >= 2){
            
                if($completionday == null){
                   
                    if($machinetime2 && $machintime){           
                        $secondT = strtotime($machintime->processing_completion_time) - strtotime($machinetime2->processing_completion_time);
                    } else{

                        if($machintime == null){
                           
                            $secondT = strtotime($status->processing_completion_time) - strtotime($machinetime2->processing_completion_time);            
                        } 
                    } 

                } else{
                    
                    if($secondtime){
                        $secondT = strtotime($completionday->processing_completion_time)- strtotime($secondtime->processing_completion_time);
                    } else{
                        $secondT = strtotime($completionday->processing_completion_time);
                    }
                }
            } else{ 
                $secondT = 0;
            }     
        } else{
            $secondT = 0;
        }
            
        $status->roll_t = $machineT;
        $status->second_t = $secondT;
      
        return $status;
    }

    public function refueling($status) //
    {
  
        $refueling = '00:00:00';
        $aggregate = '00:00:00';
 
         //累計剛好只有一筆資料 會找不到
         
        if($status['refueling_end'] != 0){     
            $end = Summary::where('refueling_end', $status['refueling_end'])->first();
            $start = Summary::where('refueling_start', $status['refueling_end'])->first();
            if($end && $start){ //有小問題
                $refueling = strtotime($end->time) - strtotime($start->time);
                $refueling = date("H:i:s", $refueling-8*60*60);
            } else{
                $refueling = '00:00:00';//如果沒有找到顯示初始值
            }     
        } else{
            $refueling = '00:00:00';
        }

        if($status['aggregate_end'] != 0){
            $end2 = Summary::where('aggregate_end', $status['aggregate_end'])->first();//累計剛好只有一筆資料
            $start2 = Summary::where('aggregate_start', $status['aggregate_end'])->first();
            if($end2 && $start2){
                $aggregate = strtotime($end2->time) - strtotime($start2->time);
                $aggregate = date("H:i:s", $aggregate-8*60*60);
            } else{
                $aggregate = '00:00:00';//如果沒有找到顯示初始值
            }
        } else{
            $aggregate = '00:00:00';
        }
        
         
        $status['refueler_time'] = $refueling;
        $status['collector_time'] = $aggregate;

        return $status;
    }

    public function calculate($data, $status)
    {
      
        $calculate262 = 0;
        $calculate263 = 0;
        $calculate363 = 0;

        if($status['actual_processing'] == 0){
            $calculate262 = 0 ;
        } else{
           if($data->orderno =='UAT-H-26-2'){
                $calculate262 = $status['actual_processing'];
           } else{
                $calculate262 = 0 ;
           }
        }

        if($status['actual_processing'] == 0){
            $calculate263 = 0 ;
        } else{
           if($data->orderno =='UAT-H-26-3'){
                $calculate263 = $status['actual_processing'];
           } else{
                $calculate263 = 0 ;
           }
        }

        if($status['actual_processing'] == 0){
            $calculate363 = 0 ;
        } else{
           if($data->orderno =='UAT-H-36-3'){
                $calculate363 = $status['actual_processing'];
           } else{
                $calculate363 = 0 ;
           }
        }

        $status['uat_h_26_2'] = $calculate262;
        $status['uat_h_26_3'] = $calculate263;
        $status['uat_h_36_3'] = $calculate363;

        return $status;
        
    }

    public function standard($data, $status)
    {
       
        $standard = StandardCt::where('orderno', $data['orderno'])->first();
        
        $standard262 = 0;
        $standard263 = 0;
        $standard363 = 0;

        if($status['uat_h_26_2'] == 0){ //一定要改
            $standard262 = 0;
        } else{
            $standard262 = $standard->standard_ct;
        }

        if($status['uat_h_26_3'] == 0){ //一定要改
            $standard263 = 0;
        } else{
            $standard263 = $standard->standard_ct;
        }

        if($status['uat_h_36_3'] == 0){ //一定要改
            $standard363 = 0;
        } else{
            $standard363 = $standard->standard_ct;
        }

        
        $status['standard_uat_h_26_2'] = $standard262;
        $status['standard_uat_h_26_3'] = $standard263;
        $status['standard_uat_h_36_3'] = $standard363;

        
        return $status;
    }
    public function break($data, $status, $description)
    {
         $time = array("08:00:00", "10:10:00", "12:00:00", "13:10:00", "15:10:00", "17:20:00", "17:50:00", "19:20:00", "19:30:00");
        
         $breaktime = "休息";
            $description->completion_status == '異常' ?
            strtotime($status->time) - strtotime($time[0]) < 0 && $data['status_id'] == 4 ? $breaktime = "休息" :
            (int)$status->time == 10 && strtotime($status->time) - strtotime($time[1]) <= 0 ? $breaktime = "休息" :
            (int)$status->time == 12 && strtotime($status->time) - strtotime($time[2]) <= 0 ? $breaktime = "休息" :
            (int)$status->time == 13 && strtotime($status->time) - strtotime($time[3]) <= 0 ? $breaktime = "休息" :
            (int)$status->time == 15 && strtotime($status->time) - strtotime($time[4]) <= 0 ? $breaktime = "休息" :
            strtotime($status->time) >= strtotime($time[5]) && strtotime($status->time) <= strtotime($time[6]) ? $breaktime = "休息" :
            strtotime($status->time) >= strtotime($time[7]) && strtotime($status->time) <= strtotime($time[8]) ? $breaktime = "休息" :
            $breaktime = "" 
            :$breaktime = "" ; 
     
            $status->break = $breaktime ;
        
        return  $status ;
    }

    public function worktime($data, $status)
    {
        
        // $hour = explode(':', $status->time)[0];
        $time = date("08:00:00");
        
        $worktime = '0';
        $before = Summary::with('resource')->where('resources_id', $data['id']-1)->first();
       
        if(isset($before)){
            if( $before->resources_id == 0 || $before->resource->date != $data['date']  ){
                (int)$status->time < (int)$time ? $worktime = '0' : $worktime = strtotime($status->time) - strtotime($time);
            } else{
                $before->time == "" ? $worktime = '0' : 
                strtotime($status->time) - strtotime($before->time) < 0 ? $worktime = '0' :
                $worktime =  strtotime($status->time) - strtotime($before->time);
            }
        } else{
            $worktime = '0';
        }
        
            $worktime = date("H:i:s", $worktime-8*3600);//
            
            $status->working_time = $worktime;
            return $status;
        
    }
    public function manufacturing($data, $status, $description)
    { 
        $manufacture = '0';

        if($status->serial_number_day < 10 && $status->open <= 1 && $data->date ){ //當天且開機小於等於1
            $manufacture = '上班' ;
        } else{
            if($data['status_id'] == 4 && $status->break == '休息' ){
                $manufacture = '休息' ;
            } else{
                if($data['status_id'] == 3 ){
                    $manufacture = '開始生產';
                } else{
                    if($data['status_id'] == 9 && $data['code'] == '500'){
                        $manufacture = "自動完工";
                    } else{
                        $manufacture = $description->completion_status;
                    }
                }
            }
        }
        
        $status->manufacturing_status = $manufacture;
        return $status;         
    }
    public function downtime($data, $status)
    {
        
        $worktime = strtotime($status->working_time) - strtotime(Carbon::today());//轉秒數
        $beforeturn =  Summary::where('turn_off', $status->open)->first();
        $beforeopen =  Summary::where('open', '<', $status->open)->orderby('open', 'desc')->first();//前一筆開機次數
        $countRestop = Summary::where('resources_id', '<', $data['id'])->sum('restop_count');
      
        $first = Summary::whereDate('created_at', '>=', Carbon::today())
        ->whereDate('created_at', '<', Carbon::tomorrow())
        ->orderby('time')
        ->first();

        $nowtime = Summary::whereDate('created_at', '>=', Carbon::today())
                    ->whereDate('created_at', '<', Carbon::tomorrow())
                    ->where('turn_off', $status['start_count'] + $countRestop)
                    ->first();
      
        if($status->open == ''){
            if( $worktime > 180 && $data['status'] == 5){
                $down_time = $status->working_time;
            } else{
                $down_time = '';
            }
        } else{
            if($status->open == 1){
                $down_time = strtotime($status->time) - strtotime($first->time);
                $down_time = date("H:i:s", $down_time-8*60*60);
            } else{
                if(isset($beforeturn)){
                    if($beforeturn->turn_off > $status->open && $status->stop_count != '' ){//判斷前面關機數量有沒有大於當前開機數量
                        if(isset($beforeopen)){
                            $down_time = strtotime($status->time) - strtotime($beforeopen->time);
                            $down_time = date("H:i:s", $down_time-8*60*60);
                        } else{
                            $down_time = strtotime($status->time) - strtotime(Carbon::today());
                            $down_time = date("H:i:s", $down_time-8*60*60);
                        }
                    } else{
                        if(isset($beforeopen)){
                            if($beforeopen->open > $status->open && $status->restart_count == ''){
        
                                $beforetime = Summary::whereDate('created_at', '>=', Carbon::today())
                                ->whereDate('created_at', '<', Carbon::tomorrow())
                                ->where('trun_off', $beforeopen['start_count'] + $countRestop)
                                ->first();

                                if($beforetime->summary != NULL){
                                    $down_time = strtotime($status->time) - strtotime($beforetime->summary->time);
                                    $down_time = date("H:i:s", $down_time-8*60*60);
                                } else{
                                    $down_time = strtotime($status->time) - strtotime(Carbon::today());
                                    $down_time = date("H:i:s", $down_time-8*60*60);
                                }
                            } else{
                                if($status->restart_count != ''){
                                    $down_time = '00:00:00';
                                } else{
                                    if($nowtime->summary != NULL){
                                        $down_time = strtotime($status->time) - strtotime($nowtime->summary->time);
                                        $down_time = date("H:i:s", $down_time-8*60*60);
                                    } else{
                                        $down_time = strtotime($status->time) - strtotime(Carbon::today());
                                        $down_time = date("H:i:s", $down_time-8*60*60);
                                    }
                                }
                            }
                        } else{
                            $down_time = '00:00:00';  
                        }
                    }
                } else{
                    $down_time = '00:00:00';
                }
            }
        }    
        $status->down_time = $down_time;

        return $status;
    }

    public function breaktime($data, $status)
    {
        $breaktime = '';
        $break =  Summary::where('turn_off', $status->open)->first();

        if($break){
            if($break->break == '休息'){    
                $breaktime = $status->down_time;
            } else{
                if($status->break == '休息' && $status->down_time != ''){
                    $breaktime = $status->down_time;
                } else{
                    $breaktime = '00:00:00';
                }
            }
        } else{
            $breaktime = '00:00:00';
        }

        $status->break_time = $breaktime;
      
        return $status;
    }
    public function refue_time($data, $status)
    {
       
        $SameDateID =  Resource::where('id', '<=', $data->id)->where('date', $data['date'])->get(['id']);
           
        $sum = Summary::whereIn('resources_id', $SameDateID)->where('refueling_start', $status->refueling_end);
            
        $refue_time = '';
        $aggregate_time = '';
        if($status->refueling_end == ''){
            $refue_time = '';
        } else{
            if($sum->count()==0){
                $refue_time="00:00:00";
            } else{
                $sumtime = 0;
                foreach($sum as $sumitem){
                    $sumitemSec = strtotime($sumitem->down_time) - strtotime(Carbon::today());
                    $refue_time = $sumtime + $sumitemSec; 
                }
            }  
        }

        if($status->aggregate_end == ''){
            $aggregate_time = '';
        } else{
            if($sum->count()==0){
                $aggregate_time="00:00:00";
            } else{
                $sumtime = 0;
                foreach($sum as $sumitem){
                    $sumitemSec = strtotime($sumitem->down_time) - strtotime(Carbon::today());
                    $aggregate_time = $sumtime + $sumitemSec; 
                }
            }
        }

        $status->refueling_time = $refue_time;
        $status->aggregate_time = $aggregate_time;
    
        return $status;
    }
    public function total($status)
    {

        $beforeID = Summary::where('resources_id', $status->resources_id - 1)->first();
        if($beforeID == NULL){
            $beforeID->machine_completion_day = 0;
        }
        $completion = Summary::where('machine_completion_day', $status->machine_completion_day)->where('resources_id', '>', 0)->first(); //找前面一筆相同的 顯示完工時間
        
        $sensro  = Summary::where('machine_inputs_day', $status->machine_completion_day-1)->where('resources_id', '>', 0)->first(); //Q4-1 = R
        $sensro2 = Summary::where('machine_inputs_day', $status->machine_completion_day-2)->where('resources_id', '>', 0)->first();//Q4-2 = R
    
        $sum = $status->machine_completion_day - $status->machine_inputs_day; //Q-R
        $sensros  = Summary::where('machine_inputs_day', $status->machine_completion_day - $sum-1)->where('resources_id', '>', 0)->first(); //Q4-(Q-R)-1 = R
        $sensros2 = Summary::where('machine_inputs_day', $status->machine_completion_day - $sum-2)->where('resources_id', '>', 0)->first();//Q4-(Q-R)-2 = R
        
        if($status->machine_completion_day > $beforeID->machine_completion_day   && $status->machine_completion_day != 1){
            
            if($status->machine_inputs_day - $status->machine_completion_day > 0){

                if(isset($sensro)){
                    if(strtotime($completion->processing_completion_time) - strtotime($sensro->processing_start_time) > 18) {
                            $total = strtotime($completion->processing_completion_time) - strtotime($sensro->processing_start_time);
                    } else{
                        if(isset($sensro2)){
                            $total = strtotime($completion->processing_completion_time) - strtotime($sensro2->processing_start_time);
                        } else{
                            $total = strtotime($completion->processing_completion_time);
                        }
                    } 
                } else{
                    if(strtotime($completion->processing_completion_time) > 18) {
                        $total = strtotime($completion->processing_completion_time);
                    } else{
                        if(isset($sensro2)){
                            $total = strtotime($completion->processing_completion_time) - strtotime($sensro2->processing_start_time);
                        } else{
                            $total = strtotime($completion->processing_completion_time);
                        }
                    } 
                }
            } else{      
                if(isset($sensros)){ //$sensros存在
                    if(strtotime($completion->processing_completion_time) - strtotime($sensros->processing_start_time) > 18) {
                            $total = strtotime($completion->processing_completion_time) - strtotime($sensros->processing_start_time);
                    } else{
                        if(isset($sensros2)){
                            $total = strtotime($completion->processing_completion_time) - strtotime($sensros2->processing_start_time);
                        } else{
                            $total = strtotime($completion->processing_completion_time);
                        }
                    }       
                } else{  //$sensros不存在
                    if(strtotime($completion->processing_completion_time > 18)){
                        $total = strtotime($completion->processing_completion_time);
                    } else{
                        if(isset($sensros2)){
                            $total = strtotime($completion->processing_completion_time) - strtotime($sensros2->processing_start_time);
                        } else{
                            $total = strtotime($completion->processing_completion_time);
                        }
                    }
                }
            }
        } else{
            $total = 0;
        }
        $sensro3  = Summary::where('machine_inputs_day', $status->machine_completion_day-3)->where('resources_id', '>', 0)->first();//Q4-3 = R
        $sensros3 = Summary::where('machine_inputs_day', $status->machine_completion_day - $sum-3)->where('resources_id', '>', 0)->first();//Q4-(Q-R)-3 = R
                
        if($status->machine_completion_day > $beforeID->machine_completion_day && $status->processing_completion_time != ""){
        
            if($total > 18 && $total < 28){
                $CTtime = $total;
            } else{
            
                if($status->machine_inputs_day > $status->machine_completion_day){
                        if(isset($sensro3)){//前面沒資料就不用相減了
                            $CTtime = strtotime($completion->processing_completion_time) - strtotime($sensro3->processing_start_time);
                        } else{
                            $CTtime = strtotime($completion->processing_completion_time);
                        } 
                } else{
                        if(isset($sensros3)){//前面沒資料就不用相減了
                            $CTtime = strtotime($completion->processing_completion_time) - strtotime($sensros3->processing_start_time);
                        } else{
                            $CTtime = strtotime($completion->processing_completion_time);
                        }         
                }
            }
        } else{
            $CTtime = 0;
        }
        $status->total_processing_time = $total;
        $status->ct_processing_time = $CTtime;
        return $status;
    }

    public function check($data)
    {
        $check = Summary::where('resources_id', $data->id)->count();
        if($check != 0){
            return True;
        } else{
            return False;
        }
    }

    public function actual($data, $status, $machine)
    {
       
        $actual = 0;
        if($machine == '捲料機1'){
            if($data['status_id'] == 9 ){
                $actual = $status->second_t;
            } else{
                if($machine == '捲料機2'){
                    if($data['status_id'] == 10){
                        $actual = $status->roll_t;
                    } else{
                        $actual = 0;
                    }
                } else{
                    $actual = 0;
                }
            }
        }
        $status['actual_processing'] = $actual;
        return $status;
    }
    public function update(array $data)
    {
        unset($data['id']);
        $Machine = Summary::where('resources_id', $data['resources_id'])->first();

        if ($Machine) {
            return $Machine->update($data);
        }
    }
   

}
