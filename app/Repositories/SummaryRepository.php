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
        'processing_completion_time','refueling_start','refueling_end','aggregate_start','aggregate_end','restart_count','restop_count')->orderby('created_at','desc')->first();
        
        $mutable = Carbon::now()->format('Y-m-d');
        
        $Statusid = Resource::where('id','>',$data['id'])->wheredate('date','=','2019-03-07')->first();  //date要等於當日
        
        $data['status'] == 3 ? $count->open++ : $count->open;
        $data['status'] == 4 ? $count->turn_off++ : $count->turn_off;
        $data['status'] == 9 ? $count->second_completion++ : $count->second_completion;
        $data['status'] == 15 ? $count->sensro_inputs++ : $count->sensro_inputs;
        
        $count->time = date("H:i:s",strtotime($data['time']));//為了換料做加減
        // dd($count->time);
        $count->serial_number++;

        if($data['orderno'] != $Statusid['orderno'] && $Statusid['id'] != null) {
            $count->machine_completion = 0;
            $count->machine_inputs = 0;
        }else{
            $data['status'] == 9 ? $count->machine_completion++ : $count->machine_completion;
            $data['status'] == 10 ? $count->machine_inputs++ : $count->machine_inputs;
            $data['status'] == 9 ? $count->processing_completion_time = $data['time'] : $count->processing_completion_time = "";
            $data['status'] == 10 ? $count->processing_start_time = $data['time'] : $count->processing_start_time = "";
        }

        if($data['date'] != $mutable) { //累積當天數量
            $count->machine_completion_day = 0;
            $count->machine_inputs_day = 0;
        }else{
            $data['status'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
            $data['status'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
            $data['status'] == 20 ? $count->refueling_start++ : $count->refueling_start;
            $data['status'] == 21 ? $count->refueling_end++ : $count->refueling_end;
            $data['status'] == 22 ? $count->aggregate_start++ : $count->aggregate_start;
            $data['status'] == 23 ? $count->aggregate_end++ : $count->aggregate_end;
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
        
        // dd($status);
        $beforeid = Resource::where('id','<',$data['id'])->orderby('created_at','desc')->first(); //上一筆status=3
        
        $openid = Summary::where('time','<',$status['time'])->orderby('created_at','desc')->first();

        if($data['status'] == '3' && $beforeid->status_id == '3'){
            if ($status->open != $openid->open && $status->open > 1){       
                $status->restart_count++;
            }else{
                $status->restart_count;
            }
        }
        else{
            $status->restart_count;
        }

        if($data['status'] == '4' && $beforeid->status_id == '4'){
            if ($status->turn_off != $openid->turn_off && $status->turn_off > 1){       
                $status->restop_count++;
            }else{
                $status->restop_count;
            }
        }
        else{
            $status->restop_count;
        }
// dd($restartopen);

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

       if($data['status'] == '10'){

            if($status->machine_inputs_day >= 2){
                $machineT = strtotime($status->processing_start_time) - strtotime($machinetime->processing_start_time);
            } else{
                $machineT = NULL;
            }     
        }elseif($data['status'] == '9'){
            
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
        $status->aggregate_time = $aggregate;

        return $status;
    }

    public function calculate($data,$status)
    {
        
        $calculate75 = '0';
        $calculate154 = '0';
        $calculate233 = '0';
        
        if($data['status'] == '9' && $status->second_t != '0' && $data['orderno'] == 'UAT-H-36-75'){
            $calculate75 = $status->second_t ;
        }else{
            $calculate75 = NULL;
        }

        if($data['status'] == '9' && $status->second_t != '0' && $data['orderno'] == 'UAT-H-36-154'){
            $calculate154 = $status->second_t ;
        }else{
            $calculate154 = NULL;
        }

        if($data['status'] == '9' && $status->second_t != '0' && $data['orderno'] == 'UAT-H-36-233'){
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

}
