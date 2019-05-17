<?php

namespace App\Repositories;

use App\Entities\Summary;
use App\Entities\Resource;
use App\Entities\StandardCt;
use Carbon\Carbon;
class SummaryRepository
{
    public function counts($data,$machine)
    {
    
        $count = Summary::whereRaw('id = (select max(`id`) from summaries)')->first();
        if($count == null){
            $count = Summary::create(['resources_id' => 0 , 'description' => '']);
        }
        //  dd($count);
        $count->id = $count->id + 1;
        $count->time = $data->time;

        $Statusid = Resource::where('id' , '<' , $data['id'])->wheredate('date' , $data['date'])->first();  //date要等於當日
        
        $oldopen = Summary::where('open' , '!=' , '')->orderby('created_at','desc')->first();
        $oldturn = Summary::where('turn_off' , '!=' , '')->orderby('created_at','desc')->first();
       
        $data['status_id'] == 3 ? $count->open = $oldopen->open + 1 : $count->open = '';
        $data['status_id'] == 4 ? $count->turn_off = $oldturn->turn_off + 1 : $count->turn_off = '';
        $data['status_id'] == 3 ? $count->start_count++ : $count->start_count;
        $data['status_id'] == 4 ? $count->stop_count++ : $count->stop_count;
        $data['status_id'] == 15 ? $count->sensro_inputs++ : $count->sensro_inputs;

        if($machine == '捲料機1'){
            $data['status_id'] == 9 ? $count->second_completion++ : $count->second_completion;
        }else{
            $data['status_id'] == 10 ? $count->second_completion++ : $count->second_completion;
        }
        
        $count->serial_number++;

        if($data['orderno'] != $Statusid['orderno'] && $Statusid['id'] == null) { //料號不相同
            $count->machine_completion = 0;
            $count->machine_inputs = 0;
        }else{

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
            }else{
                if($data['status_id'] == 10){
                    $count->processing_start_time = $data['time'];
                }else{
                    $completion = Summary::where('machine_completion_day' , $count->machine_completion_day-1)->first();
                    $restart = Summary::where('start_count' , $count->start_count)->first();//建一個累加開或關的
                    if($completion){
                        if($completion->machine_completion_day > 0){
                            $count->processing_start_time = $completion->time;
                        }else{
                            if($restart){
                                $count->processing_start_time = $restart->time;
                            }     
                        }
                    }
                }
                 
            }
                    
        }

            if(!$data->date) { //累積當天數量
                $count->machine_completion_day = 0;
                $count->machine_inputs_day = 0;
            }else{
                if ($machine == '捲料機1'){
                    $data['status_id'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
                } else{
                    $data['status_id'] == 10 ? $count->machine_completion_day++ : $count->machine_completion_day;
                }
                $oldrefs = Summary::where('refueling_start' , $count->refueling_start)->orderby('created_at','desc')->first();
                $oldrefe = Summary::where('refueling_end' , $count->refueling_end)->orderby('created_at','desc')->first();
                $oldags = Summary::where('aggregate_start' , $count->aggregate_start)->orderby('created_at','desc')->first();
                $oldage = Summary::where('aggregate_end' , $count->aggregate_end)->orderby('created_at','desc')->first();

               
                $data['status_id'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
                $data['status_id'] == 20 ? $count->refueling_start = $oldrefs->refueling_start+1 : $count->refueling_start =0;
                $data['status_id'] == 21 ? $count->refueling_end = $oldrefe->refueling_end : $count->refueling_end = 0;
                $data['status_id'] == 22 ? $count->aggregate_start = $oldags->aggregate_start+1 : $count->aggregate_start = 0;
                $data['status_id'] == 23 ? $count->aggregate_end = $oldage->aggregate_end+1: $count->aggregate_end = 0;
            }

            
            if ($data['orderno'] == '' && !$data['date']){
                $count->serial_number_day = 1 ;
            } else {
                if($data['orderno'] == '' && $data['date']){
                        $Status = Summary::where('resources_id' , $Statusid->id)->first();//前一筆serial +1
                        if($Status){
                            $count->serial_number_day = $Status->serial_number_day+1;
                        } else{
                            $count->serial_number_day++;
                        }
                } else{  //前面加總料號數量+1 且同料號同日期
                    // dd(1234);
                    $serial = Resource::where('orderno' , $data['orderno'])->where('date',$data['date'])->orderby('time','desc')->first();
                    //  dd($serial->id);
                    $Statusid = Summary::where('resources_id' , $serial->id)->first();
                    if($Statusid){
                        $count->serial_number_day = $Statusid->serial_number_day+1;
                    }else{
                        $count->serial_number_day++ ;
                    }       
                }

            }

        return $count;
    }

    public function restart($data,$status)
    {   
    // dd($status);
       if($status->open != ''){
         $restart = Summary::where('open',$status['open']-1)->first(); //上一筆開機關機

        if($status['open'] == ''){
            $status["restart_count"] = '';
        }else{
            if($status['open'] == '1'){
                $status["restart_count"] = '' ;
            }else{
                    if($restart->open == $status['open'] && $date['date'] ){
                        $status["restart_count"] = $restart->restart_count++;
                    }else{
                        $status["restart_count"] = '' ;
                    }
                 }
        }

        }else{
        $status["restart_count"] = "";
        }

        if($status['turn_off'] != ''){

        $restop = Summary::where('turn_off',$status['turn_off']-1)->first();
       
        if($status['turn_off'] == ''){
            $status['restop_count'] = '';
        }else{
            if($status['turn_off'] == '1'){
                $status['restop_count'] = '' ;
        }else{
                    if($restop->turn_off ==  $status['turn_off'] && $date['date']){
                        $status['restop_count'] = $restop->restop_count++;
                    }else{
                        $status['restop_count'] = '' ;
                    }
                }
        }

    }else{
        $status['restop_count'] = "";
        }

        return $status;
    }

    public function create($data)
    {
        // dd($data);
       return Summary::create($data);
    }
    public function update(Array $data)
    {
        //  dd($data);
        unset($data['id']);
        $Machine = Summary::where('resources_id',$data['resources_id'])->first();
        
        if ($Machine) {
            return $Machine->update($data);
        }

        return false;
    }

    public function machineT($data,$status,$machine)
    {
        //  dd($status);
        $machinetime = Summary::where('machine_inputs_day',$status['machine_inputs_day']-1)->first();
        
        $secondtime = Summary::where('machine_completion_day',$status['machine_completion_day']-1)->first();
        $completionday = Summary::where('machine_completion_day',$status['machine_inputs_day'])->first();
        $machinetime2 = Summary::where('machine_completion',$status['machine_completion']-1)->first();
        
        $machineT = 0;
        $secondT = 0;
        
        if($machine == '捲料機1'){
            if ($data['status_id'] == 10) {

                    if($status->machine_inputs_day >= 2){
                        if($machinetime->processing_start_time){
                            $machineT = strtotime($status->processing_start_time) - strtotime($machinetime->processing_start_time);
                            // dd($status->processing_start_time);
                        }else{
                            $machineT = 0;
                        }
                    } 
                    else{
                        $machineT = 0;
                    }     
            }else{
                $machineT = 0;
            }
        }else{
            if ($data['status_id'] == 10){
                $machineT = strtotime($status->processing_completion_time) - strtotime($status->processing_start_time);
            }else{
                $machineT = 0;
            }
        }
        

        if($data['status_id'] == '9'){
             
            if($status->machine_completion_day >= 2){
                if($completionday){
                    if($machinetime2->processing_completion_time){
                        $secondT = strtotime($status->processing_completion_time) - strtotime($machinetime2->processing_completion_time);
                    }else{
                        $secondT = strtotime($status->processing_start_time);
                    }
                } else{
                    if($machinetime->processing_start_time){
                        $secondT = strtotime($status->processing_completion_time) - strtotime($machinetime->processing_completion_time);
                    }else{
                        $secondT = strtotime($status->processing_start_time);
                    }    
                } 
            } else{ 
                $secondT = 0;
            }     
        }else{
            $secondT = 0;
        }

        $status->roll_t = $machineT;
        $status->second_t = $secondT;
       
        return $status;
    }

    public function refueling($status) //
    {
        //    dd($status);
        $refueling = '00:00:00';
        $aggregate = '00:00:00';
 
         //累計剛好只有一筆資料 會找不到
            
        //  dd($status);
         
            if($status['refueling_end'] != 0){     
                $end = Summary::where('refueling_end',$status['refueling_end'])->first();
                $start = Summary::where('refueling_start',$status['refueling_end'])->first();
                    if($end && $start){ //有小問題
                        $refueling = strtotime($end->time) - strtotime($start->time);
                        $refueling = date("H:i:s",$refueling-8*60*60);
                    }else{
                        $refueling = '00:00:00';//如果沒有找到顯示初始值
                    }     
            }else{
                $refueling = '00:00:00';
            }
    
            if($status['aggregate_end'] != 0){
                $end2 = Summary::where('aggregate_end',$status['aggregate_end'])->first();//累計剛好只有一筆資料
                $start2 = Summary::where('aggregate_start',$status['aggregate_end'])->first();
                if($end2 && $start2){
                    $aggregate = strtotime($end2->time) - strtotime($start2->time);
                    $aggregate = date("H:i:s",$aggregate-8*60*60);
                }else{
                    $aggregate = '00:00:00';//如果沒有找到顯示初始值
                }
            }else{
                $aggregate = '00:00:00';
            }
        
         
        $status['refueler_time'] = $refueling;
        $status['collector_time'] = $aggregate;

        return $status;
    }

    public function calculate($data,$status)
    {
      
        $calculate262 = 0;
        $calculate263 = 0;
        $calculate363 = 0;

        if($status['actual_processing'] == 0){
            $calculate262 = 0 ;
        }else{
           if($data->orderno =='UAT-H-26-2'){
                $calculate262 = $status['actual_processing'];
           }else{
                $calculate262 = 0 ;
           }
        }

        if($status['actual_processing'] == 0){
            $calculate263 = 0 ;
        }else{
           if($data->orderno =='UAT-H-26-3'){
                $calculate263 = $status['actual_processing'];
           }else{
                $calculate263 = 0 ;
           }
        }

        if($status['actual_processing'] == 0){
            $calculate363 = 0 ;
        }else{
           if($data->orderno =='UAT-H-36-3'){
                $calculate363 = $status['actual_processing'];
           }else{
                $calculate363 = 0 ;
           }
        }

        

        $status['uat_h_26_2'] = $calculate262;
        $status['uat_h_26_3'] = $calculate263;
        $status['uat_h_36_3'] = $calculate363;
        //  dd($status);

        return $status;
        
    }

    public function standard($data,$status)
    {
       
        $standard = StandardCt::where('orderno',$data['orderno'])->first();
        
        $standard262 = 0;
        $standard263 = 0;
        $standard363 = 0;

        if($status['uat_h_26_2'] == 0){ //一定要改
            $standard262 = 0;
        }else{
            $standard262 = $standard->standard_ct;
        }

        if($status['uat_h_26_3'] == 0){ //一定要改
            $standard263 = 0;
        }else{
            $standard263 = $standard->standard_ct;
        }

        if($status['uat_h_36_3'] == 0){ //一定要改
            $standard363 = 0;
        }else{
            $standard363 = $standard->standard_ct;
        }

        
        $status['standard_uat_h_26_2'] = $standard262;
        $status['standard_uat_h_26_3'] = $standard263;
        $status['standard_uat_h_36_3'] = $standard363;

        
        return $status;
    }
    public function break($data,$status,$description)
    {
         $time = array("08:00:00","10:10:00","12:00:00","13:10:00","15:10:00","17:20:00","17:50:00","19:20:00","19:30:00");
       
         $hour = explode(':',$status->time)[0];
        // $breaktime = '0';
         
         $breaktime = "休息";
            $description->completion_status == '異常' ?
            strtotime($status->time) - strtotime($time[0]) < 0 && $data['status_id'] =='4' ? $breaktime = "休息" :
            $hour == "10" && strtotime($status->time) - strtotime($time[1]) <= 0 ? $breaktime = "休息" :
            $hour == "12" && strtotime($status->time) - strtotime($time[2]) <= 0 ? $breaktime = "休息" :
            $hour == "13" && strtotime($status->time) - strtotime($time[3]) <= 0 ? $breaktime = "休息" :
            $hour == "15" && strtotime($status->time) - strtotime($time[4]) <= 0 ? $breaktime = "休息" :
            strtotime($status->time) >= strtotime($time[5]) && strtotime($status->time) <= strtotime($time[6]) ? $breaktime = "休息" :
            strtotime($status->time) >= strtotime($time[7]) && strtotime($status->time) <= strtotime($time[8]) ? $breaktime = "休息" :
            $breaktime = "" 
            :$breaktime = "" ; 
     
            $status->break = $breaktime ;
        
        return  $status ;
    }

    public function worktime($data,$status)
    {
        $hour = explode(':',$status->time)[0];
        $time = date("08:00:00");

        $worktime = '0';
        //  dd($time);
        $beforeid = Resource::where('id','<',$data['id'])->wheredate('date','=',$data['date'])->first();
        
        if($beforeid != null){
            if($beforeid->date != $data['date']){
                strtotime($hour) <= strtotime($time) ? $worktime = '0' : strtotime($status->time) + strtotime($time);
            } else{
                $status->time == "" ? $worktime = '0' : 
                strtotime($status->time) - strtotime($beforeid->time) < 0 ? $worktime = '0':
                $worktime =  strtotime($status->time) - strtotime($beforeid->time) ;
            }
        
            $worktime = date("H:i:s",$worktime-8*60*60);

            // dd($worktime);
            $status->working_time = $worktime;
            return $status;
        }else{
            $status->working_time = NULL;
            return $status;
        }
        
    }
    public function manufacturing($data,$status,$description)
    { 
         $manufacture = '0';

        if($status->serial_number_day < 10 && $status->open <= 1 && $data->date ){ //當天且開機小於等於1
            $manufacture = '上班' ;
        }else{
            if($data['status_id'] == '4' && $status->break == '休息' ){
                $manufacture = '休息' ;
            }else{
                if($data['status_id'] == '3'){
                    $manufacture = '開始生產';
                }else{
                    if($data['status_id'] == '9' && $data['code'] == '500'){
                        $manufacture = "自動完工";
                    }else{
                        $manufacture = $description->completion_status;
                    }
                }
            }
        }
        
        $status->manufacturing_status = $manufacture;
        return $status;         
    }
    public function downtime($data,$status)
    {
        // dd($status->time);
        // dd(Summary::find(1)->first()->resources_id);
        
        $worktime =  strtotime($status->working_time) - strtotime(Carbon::parse($status->working_time)->format('Y-m-d'));
        
        $first = Resource::where('date',$data['date'])->first();
        
        $beforeturn =  Summary::where('turn_off' , $status->open)->first();
        if($status->open == 0 && $status->open == ''){
            $lastOpenCount = 0;
        }else{
            $lastOpenCount = $status->open -1;
        }
        $beforeopen =  Summary::where('open' , $lastOpenCount)->first();//前一筆開機次數
        $closeturn =  Summary::where('turn_off' , $status->open)->first();
        $breaktime = '0';
        
        if($status->open == ''){

            if( $worktime> 180 && $data['status'] == '5'){
                $breaktime = $status->working_time;
            }else{
                $breaktime = '';
            }
            
        }else{

            if($status->open == '1'){
                $breaktime = strtotime($status->time) - strtotime($first->time);//現在的時間減當天的最早的時間
                $breaktime = date("H:i:s",$breaktime-8*60*60);
            }else{
                if($status->restop_count != '' && $beforeturn->turn_off > $status->open){//判斷前面關機數量有沒有大於當前開機數量
                    $breaktime = strtotime($status->time) - strtotime($beforeopen->open);
                    $breaktime = date("H:i:s",$breaktime-8*60*60);
                }else{
                    if($beforeopen != NULL){
                        $lastopen = $beforeopen->id;
                    }else{
                        $lastopen = 0;
                    }
                    if($closeturn != NULL){
                        $lastturn = $closeturn->id;
                    }else{
                        $lastturn = 0;
                    }

                    if($lastopen > $lastturn && $status->restart_count == "" ){
                        if($beforeopen != NULL){
                            // dd($beforeopen);
                            $countoff = Resource::where('id','<=',$beforeopen->resources_id)->where('code',4)->count(); //累積關機次數
                            $SameDateID =  Resource::where('id','<=',$data['id'])->where('date',$data['date'])->get(['id']);
                            $countRestop = Summary::whereIn('resources_id',$SameDateID)->sum('restop_count');
                            $Dtime = Summary::whereIn('resources_id',$SameDateID)->where('turn_off',$countoff+$countRestop);
                          
                            if($Dtime->count() != 0 ){    
                                $breaktime =  strtotime($data->time) - strtotime($Dtime->time);
                                $breaktime = date("H:i:s",$breaktime-8*60*60);
                            }else{
                                $breaktime = $data['time'];
                            }

                        }else{
                            $countoff = 0;
                        }
                        // $breaktime = strtotime($status->time) - 

                    }else{
                        if($status->restart_count != ""){
                            $breaktime = '0';
                        }else{
                            $currentoff =  Resource::where('id','<=',$data->id)->where('code',4)->count();
                            $SameDateID =  Resource::where('id','<=',$data->id)->where('date',$data['date'])->get(['id']);
                            $Dtime = Summary::whereIn('resources_id',$SameDateID)->where('turn_off',$currentoff);
                            if($Dtime->count()==0){
                                $Dtime->time = '00:00:00';
                            }
                            $breaktime =  strtotime($data->time) - strtotime($Dtime->time);
                            $breaktime = date("H:i:s",$breaktime-8*60*60);
                        }
                    }
                }
            }
            
        }

        $status->down_time = $breaktime;
        
        return $status;
    }

    public function breaktime($data,$status)
    {
        $breaktime = '';
        $break =  Summary::where('turn_off',$status->open)->first();

        if($break){
            if($break->break == '休息'){    
                $breaktime = $status->down_time;
        }
        else{
                if($status->break == '休息' && $status->down_time != ''){
                    $breaktime = $status->down_time;
                }else{
                    $breaktime = '00:00:00';
                }
            }
        }else{
            $breaktime = '00:00:00';
        }

       $status->break_time = $breaktime;
      
        return $status;
    }
    public function refue_time($data,$status)
    {
       
            $SameDateID =  Resource::where('id' , '<=' , $data->id)->where('date' , $data['date'])->get(['id']);
            // dd($SameDateID);
            $sum = Summary::whereIn('resources_id' , $SameDateID)->where('refueling_start' , $status->refueling_end);
        
          
        $refue_time = '';
        $aggregate_time = '';
        if($status->refueling_end == ''){
            $refue_time = '';
        }else{
                if($sum->count()==0){
                    $refue_time="00:00:00";
                } else{
                    $sumtime = 0;
                    foreach($sum as $sumitem){
                        $sumitemSec = strtotime($sumitem->down_time) - strtotime(Carbon::parse($sumitem->down_time)->format('Y-m-d'));
                        $refue_time = $sumtime + $sumitemSec; 
                    }
             }
            
        }

        if($status->aggregate_end == ''){
            $aggregate_time = '';
        }else{
            if($sum->count()==0){
                $aggregate_time="00:00:00";
            } else{
                $sumtime = 0;
                foreach($sum as $sumitem){
                    $sumitemSec = strtotime($sumitem->down_time) - strtotime(Carbon::parse($sumitem->down_time)->format('Y-m-d'));
                    $aggregate_time = $sumtime + $sumitemSec; 
                }
            }
        }

        $status->refueling_time = $refue_time;
        $status->aggregate_time = $aggregate_time;

        
        return $status;
    }
    public function total($data,$status)
    {
        
    $beforeID = Summary::whereRaw('id = (select max(`id`) from summaries)')->first();
    if($status->machine_completion_day || $beforeID->machine_completion_day){

    $completion = Summary::where('machine_completion_day', $status->machine_completion_day)->first(); //找前面一筆相同的 顯示完工時間
        if($completion){
    $sensro = Summary::where('sensro_inputs' , $status->machine_completion_day-1)->first(); //Q4-1 = R
    $sensro2 = Summary::where('sensro_inputs' , $status->machine_completion_day-2)->first();//Q4-2 = R
    $sensro3 = Summary::where('sensro_inputs' , $status->machine_completion_day-3)->first();//Q4-3 = R

    $sum = $status->machine_completion_day - $status->machine_inputs_day; //Q-R
    $sensros = Summary::where('sensro_inputs' , $status->machine_completion_day - ($sum-1))->first(); //Q4-(Q-R)-1 = R
    $sensros2 = Summary::where('sensro_inputs' , $status->machine_completion_day - ($sum-2))->first();//Q4-(Q-R)-2 = R
    $sensros3 = Summary::where('sensro_inputs' , $status->machine_completion_day - ($sum-3))->first();//Q4-(Q-R)-3 = R
        
       
                    if($status->machine_completion_day > $beforeID->machine_completion_day   && $status->machine_completion_day != 1){
                        if($status->machine_inputs_day - $status->machine_completion_day > 0){
                                if(strtotime($completion->processing_completion_time) - strtotime($sensro->processing_completion_time) > 18) {
                                    if($sensro = null ){//前面沒資料就不用相減了
                                        $total = strtotime($completion->processing_completion_time);
                                    }else{
                                        $total = strtotime($completion->processing_completion_time) - strtotime($sensro->processing_completion_time);
                                    }
                                }else{
                                    if($sensro2 = null ){
                                        $total = strtotime($completion->processing_completion_time);
                                    }else{
                                        $total = strtotime($completion->processing_completion_time) - strtotime($sensro2->processing_completion_time);
                                    }
                                } 

                                if(strtotime($completion->processing_completion_time) - strtotime($sensros->processing_completion_time) > 18) {
                                    if($sensros = null ){
                                        $total = strtotime($completion->processing_completion_time);
                                    }else{
                                        $total = strtotime($completion->processing_completion_time) - strtotime($sensros->processing_completion_time);
                                    }
                                }else{
                                    if($sensros2 = null ){
                                        $total = strtotime($completion->processing_completion_time);
                                    }else{
                                        $total = strtotime($completion->processing_completion_time) - strtotime($sensros2->processing_completion_time);
                                    }
                                }       
                        }

                    }else{
                        $total = 0;
                    }
        
                    if($status->machine_completion_day > $beforeID->machine_completion_day && $status->processing_completion_time != ''){
                        
                        if($total > 18 && $total < 28){
                            $CTtime = $total;
                        }else{
                            if($status->machine_inputs_day > $status->machine_completion_day){
                                    if($sensro3 = null ){//前面沒資料就不用相減了
                                        $CTtime = strtotime($completion->processing_completion_time);
                                    }else{
                                        $CTtime = strtotime($completion->processing_completion_time) - strtotime($sensro3->processing_completion_time);
                                    } 
                            }else{
                                if($sensro3 = null){//前面沒資料就不用相減了
                                    $CTtime = strtotime($completion->processing_completion_time);
                                }else{
                                    $CTtime = strtotime($completion->processing_completion_time) - strtotime($sensros3->processing_completion_time);
                                }         
                            }
                        }
                    }else{
                        $CTtime = 0;
                    }

        }else{
            $total = 0;
            $CTtime = 0;
        }   
        
    }else{
        $total = 0;
        $CTtime = 0;
    }
   

        $status->total_processing_time = $total;
        $status->ct_processing_time = $CTtime;

        
        return $status;

        
    }

    public function check($data)
    {
        $check = Summary::where('resources_id',$data->id)->count();
        if($check != 0){
            return True;
        }else{
            return False;
        }
    }

    public function actual($data,$status,$machine)
    {
        
        $actual = 0;
        if($machine == '捲料機1'){
            if($data['status_id'] == 9 ){
                $actual = $status->second_t = 0;
            }else{
                if($machine == '捲料機2'){
                    if($data['status_id'] == 10){
                        $actual = $status->roll_t;
                    }else{
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

}
