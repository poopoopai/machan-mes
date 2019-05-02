<?php

namespace App\Repositories;

use App\Entities\Summary;
use App\Entities\Resource;
use App\Entities\StandardCt;
use Carbon\Carbon;
class SummaryRepository
{
    public function counts($data)
    {
        
        $count = Summary::select('serial_number','serial_number_day','open','turn_off','time','machine_completion','machine_inputs',
        'sensro_inputs','machine_completion_day','machine_inputs_day','second_completion','processing_start_time',
        'processing_completion_time','refueling_start','refueling_end','aggregate_start','aggregate_end','restart_count','restop_count'
        )->orderby('created_at','desc')->first();
        
        $mutable = Carbon::now()->format('Y-m-d');
        
        $Statusid = Resource::where('id','>',$data['id'])->wheredate('date','=','2019-03-07')->first();  //date要等於當日


        $oldopen = Summary::where('open','!=','')->orderby('created_at','desc')->first();
        $oldturn = Summary::where('turn_off','!=','')->orderby('created_at','desc')->first();
       
        $data['status_id'] == 3 ? $count->open = $oldopen->open + 1 : $count->open = '';
        $data['status_id'] == 4 ? $count->turn_off = $oldturn->turn_off + 1 : $count->turn_off = '';
        $data['status_id'] == 9 ? $count->second_completion++ : $count->second_completion;
        $data['status_id'] == 15 ? $count->sensro_inputs++ : $count->sensro_inputs;
        
        $count->time = date("H:i:s",strtotime($data['time']));//為了換料做加減
        // dd($count->time);
        $count->serial_number++;

        if($data['orderno'] != $Statusid['orderno'] && $Statusid['id'] != null) {
            $count->machine_completion = 0;
            $count->machine_inputs = 0;
        }else{
            $data['status_id'] == 9 ? $count->machine_completion++ : $count->machine_completion;
            $data['status_id'] == 10 ? $count->machine_inputs++ : $count->machine_inputs;
            $data['status_id'] == 9 ? $count->processing_completion_time = $data['time'] : $count->processing_completion_time = "";
            $data['status_id'] == 10 ? $count->processing_start_time = $data['time'] : $count->processing_start_time = "";
        }

        if($data['date'] != $mutable) { //累積當天數量
            $count->machine_completion_day = 0;
            $count->machine_inputs_day = 0;
        }else{
            $data['status_id'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
            $data['status_id'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
            $data['status_id'] == 20 ? $count->refueling_start++ : $count->refueling_start;
            $data['status_id'] == 21 ? $count->refueling_end++ : $count->refueling_end;
            $data['status_id'] == 22 ? $count->aggregate_start++ : $count->aggregate_start;
            $data['status_id'] == 23 ? $count->aggregate_end++ : $count->aggregate_end;
        }

        if(($data['orderno'] != $Statusid['orderno']&&$Statusid['id'] != null)||($data['date'] != $mutable)){
            $count->serial_number_day = 0 ;
        }else{
            $count->serial_number_day++;
        }

        return $count;
    }

    public function restart($data,$status)
    {   
       
         $before = Summary::where('time','<',$data['time'])->orderby('created_at','desc')->first(); //上一筆開機關機
         if($before!=null){
        //   dd($before);
         $restart = Summary::where('restart_count','!=','')->orderby('created_at','desc')->first();
         $restop = Summary::where('restop_count','!=','')->orderby('created_at','desc')->first();
// dd($openid->open);

        if($status->open == ''){
            $status->restart_count = '';
        }elseif($status->open == '1'){
            $status->restart_count = '' ;
        }elseif($before->open != '' && $before->open < $status->open ){ 
            $status->restart_count = $restart->restart_count + 1;
        }else{
            $status->restart_count = '' ;
        }

        if($status->turn_off == ''){
            $status->restop_count = '';
        }elseif($status->open == '1'){
            $status->restop_count = '' ;
        }elseif($before->turn_off != '' && $before->turn_off < $status->turn_off ){ //錯誤
            $status->restop_count = $restop->restop_count + 1;
        }else{
            $status->restop_count = '' ;
        }

    }else{
        $status->open = "";
    }
        return $status;
    }

    public function create($data)
    {
       return Summary::create($data);
    }
    public function update($id,Array $data)
    {
        
        $Machine = Summary::find($id);

        if ($Machine) {
            return $Machine->update($data);
        }
        return false;
    }

    public function machineT($data,$status)
    {
            //  dd($status);
        
        // $mutable = Carbon::now()->format('Y-m-d');
        $machinetime = Summary::where('machine_inputs_day','=',$status['machine_inputs_day']-1)->orderby('created_at','asc')->first();
        $secondtime = Summary::where('machine_completion_day','=',$status['machine_completion_day']-1)->orderby('created_at','asc')->first();
        // dd($machinetime);
        $machineT = '0';
        $secondT = '0';
        //  dd($Statustime);
        // $min = 60;
        // dd($status->machine_inputs_day);

       if($data['status_id'] == '10'){

            if($status->machine_inputs_day >= 2){
                $machineT = strtotime($status->processing_start_time) - strtotime($machinetime->processing_start_time);
            } else{
                $machineT = NULL;
            }     
        }elseif($data['status_id'] == '9'){
            
            if($status->machine_completion_day >= 2){
                $secondT = strtotime($status->processing_completion_time) - strtotime($secondtime->processing_completion_time);
            } else{
                $secondT = NULL;
            }     
        }else{
            $machineT = NULL;
            $secondT = NULL;
        }

        $machineT = date("H:i:s",$machineT-8*60*60);
        $secondT = date("H:i:s",$secondT-8*60*60);

        $status->roll_t = $machineT;
        $status->second_t = $secondT;

        return $status;
    }

    public function refueling($status) //
    {
        
        $refueling_start = Summary::where('refueling_start','=',$status['refueling_start'])->orderby('created_at','asc')->first();

        $aggregate_start = Summary::where('aggregate_start','=',$status['aggregate_start'])->orderby('created_at','asc')->first();
        
    //   dd($refueling_start);
//   dd($status->time);
        $refueling = '0';
        $aggregate = '0';

        if($status->refueling_end != '0'){
            if($status->refueling_start){
                $refueling = strtotime($status->time) - strtotime($refueling_start->time);
                // dd($refueling);
            }else{
                $refueling = '0';
            }
            // $status->time - $refueling_start->time
        }

        if($status->aggregate_end != '0'){
            if($status->aggregate_start){
                $aggregate = strtotime($status->time) - strtotime($aggregate_start->time);
                // dd($refueling);
            }else{
                $aggregate = '0';
            }
            // $status->time - $refueling_start->time
        }
  
        $refueling = date("H:i:s",$refueling-8*60*60); //修正 8小時
        $aggregate = date("H:i:s",$aggregate-8*60*60);
        
        $status->refueler_time = $refueling;
        $status->collector_time = $aggregate;

        return $status;
    }

    public function calculate($data,$status)
    {
        
        $calculate75 = '0';
        $calculate154 = '0';
        $calculate233 = '0';
        
        if($data['status_id'] == '9' && $status->second_t != '0' && $data['orderno'] == 'UAT-H-36-75'){
            $calculate75 = $status->second_t ;
        }else{
            $calculate75 = NULL;
        }

        if($data['status_id'] == '9' && $status->second_t != '0' && $data['orderno'] == 'UAT-H-36-154'){
            $calculate154 = $status->second_t ;
        }else{
            $calculate154 = NULL;
        }

        if($data['status_id'] == '9' && $status->second_t != '0' && $data['orderno'] == 'UAT-H-36-233'){
            $calculate233 = $status->second_t ;
        }else{
            $calculate233 = NULL;
        }

        $status->uat_h_36_75 = $calculate75;
        $status->uat_h_36_154 = $calculate154;
        $status->uat_h_36_233 = $calculate233;
        // dd($status);

        return $status;
        
    }

    public function standard($data,$status)
    {
        
        $standard = StandardCt::where('orderno','=',$data['orderno'])->first();
        //    dd($status);
        $standard75 = '0';
        $standard154 = '0';
        $standard233 = '0';

        if($status->uat_h_36_75 && $status->uat_h_36_75 != "00:00:00"){ //一定要改
            $standard75 = $standard->standard_ct;
        }else{
            $standard75 = NULL;
        }
        if($status->uat_h_36_154 && $status->uat_h_36_154 != "00:00:00"){
            $standard154 = $standard->standard_ct;
        }else{
            $standard154 = NULL;
        }
        if($status->uat_h_36_233 && $status->uat_h_36_233 != "00:00:00"){
            $standard233 = $standard->standard_ct;
        }else{
            $standard233 = NULL;
        }
//   dd($standard75);
        $status->standard_uat_h_36_75 = $standard75;
        $status->standard_uat_h_36_154 = $standard154;
        $status->standard_uat_h_36_233 = $standard233;

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
            // if($hour < $time)
        
    }
    public function manufacturing($data,$status,$description)
    { 
        $mutable =  Carbon::today()->format('Y-m-d');
        // dd($mutable);
        //    dd($status->break);
        
         $manufacture = '0';
        // // dd($manufacture);
        if($status->serial_number_day < 10 && $status->open <= 1 && $mutable ){ //當天且開機小於等於1
            $manufacture = '上班' ;
        }elseif($data['status_id'] == '4' && $status->break == '休息' ){
            $manufacture = '休息' ;
        }elseif($data['status_id'] == '3'){
            $manufacture = '開始生產';
        }elseif($data['status_id'] == '9' && $data['code'] == '500'){
            $manufacture = "自動完工";
        }else{
            $manufacture = $description->completion_status;
        }

        $status->manufacturing_status = $manufacture;
        return $status;
        // // $data['status'] == '4' && $status->break == '休息'? $manufacture = '休息' : 
        // // $data['status'] == '3' ? $manufacture = '開始生產' :
        // // // $data['status'] == '9' && $data['code'] == '500' ? $manufacture = "自動完工" :
        // // $manufacture = $status->completion_status ;
        
        //  dd($manufacture);
    }
    public function downtime($data,$status)
    {
        // dd($status->time);
        // dd(Summary::find(1)->first()->resources_id);
        
        $worktime =  strtotime($status->working_time) - strtotime(Carbon::parse($status->working_time)->format('Y-m-d'));
        
        $first = Resource::where('date',$data['date'])->first();
        
        $beforeturn =  Summary::where('turn_off','=',$status->open)->first();
        if($status->open == 0 && $status->open == ''){
            $lastOpenCount = 0;
        }else{
            $lastOpenCount = $status->open -1;
        }
        $beforeopen =  Summary::where('open','=',$lastOpenCount)->first();//前一筆開機次數
        $closeturn =  Summary::where('turn_off','=',$status->open)->first();
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
                            $countoff = Resource::where('id','<=',$beforeopen->resource_id)->where('code',4)->count(); //累積關機次數
                            $SameDateID =  Resource::where('id','<=',$data->id)->where('date',$data['date'])->get([id]);
                            $countRestop = Summary::whereIn('resources_id',$SameDateID)->sum('restop_count');
                            $Dtime = Summary::whereIn('resources_id',$SameDateID)->where('turn_off',$countoff+$countRestop);
                            if($Dtime->count() != 0){
                                $breaktime =  strtotime($data->time) - strtotime($Dtime->time);
                                $breaktime = date("H:i:s",$breaktime-8*60*60);
                            }else{
                                $breaktime = $data->time;
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
                            $SameDateID =  Resource::where('id','<=',$data->id)->where('date',$data['date'])->get([id]);
                            $Dtime = Summary::whereIn('resources_id',$SameDateID)->where('turn_off',$currentoff);
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

        if($status->open == ""){    //判斷小問題
            $breaktime = '';
        }else{
            $breaktime = $status->down_time;
        }

       if($status->time == '9' && $status->down_time != ''){
         $breaktime = $status->down_time;
       }else{
        $breaktime = '';
       }

       $status->breaktime = $breaktime;
        
        return $status;
    }
    public function refue_time($data,$status)
    {

        if($data['date']){ //當天
            $sum = Summary::where('resources_id',$data->id)->orwhere('refueling_start',$status->refueling_end)->first();
        }else{
            $sum = "";
        }
          
        $refue_time = '';
        $aggregate_time = '';
        if($status->refueling_end == ''){
            $refue_time = '';
        }else{
            $refue_time = $sum->breaktime;
        }
        if($status->aggregate_end == ''){
            $aggregate_time = '';
        }else{
            $aggregate_time = $sum->breaktime;
        }

        $status->refueling_time = $refue_time;
        $status->aggregate_time = $aggregate_time;

        dd($status);
        return $status;
    }
}
