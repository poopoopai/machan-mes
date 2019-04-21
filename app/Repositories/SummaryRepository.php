<?php

namespace App\Repositories;

use App\Entities\Summary;
use App\Entities\Resource;
use Carbon\Carbon;
class SummaryRepository
{
    public function counts($data)
    {
        
        $count = Summary::select('serial_number','serial_number_day','open','turn_off','machine_completion','machine_inputs',
        'sensro_inputs','machine_completion_day','machine_inputs_day','second_completion',
        'processing_start_time','processing_completion_time')->orderby('created_at','desc')->first();
        
        $mutable = Carbon::now()->format('Y-m-d');
        
        $Statusid = Resource::where('id','>',$data['id'])->wheredate('date','=','2019-03-07')->first();  
        
        $data['status'] == 3 ? $count->open++ : $count->open;
        $data['status'] == 4 ? $count->turn_off++ : $count->turn_off;
        $data['status'] == 15 ? $count->sensro_inputs++ : $count->sensro_inputs;
        $data['status'] == 9 ? $count->second_completion++ : $count->second_completion;
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

        if(!$mutable) {
            $count->machine_completion_day = 0;
            $count->machine_inputs_day = 0;
        }else{
            $data['status'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
            $data['status'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
        }

        if(($data['orderno'] != $Statusid['orderno']&&$Statusid['id'] != null)||($data['date'] != $mutable)){
            $count->serial_number_day = 0 ;
        }else{
            $count->serial_number_day++;
        }

        return $count;
    }

    public function create($data)
    {
       return Summary::create($data);
    }

    public function machineT($data,$status)
    {
            // dd($status);
        
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
                $machineT = '0';
            }     
        }elseif($data['status'] == '9'){
            
            if($status->machine_completion_day >= 2){
                $secondT = strtotime($status->processing_completion_time) - strtotime($secondtime->processing_completion_time);
            } else{
                $secondT = '0';
            }     
        }else{
            $machineT = '0';
            $secondT = '0';
        }

        $status->roll_t = $machineT;
        $status->second_t = $secondT;

        return $status;
        
        
    }

}
