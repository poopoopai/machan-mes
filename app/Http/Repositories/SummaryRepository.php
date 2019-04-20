<?php

namespace App\Http\Repositories;

use App\Entities\Summary;
use App\Entities\Resource;
use Carbon\Carbon;
class SummaryRepository
{
    public function counts($data)
    {
        
        $count = Summary::select('open','turn_off','machine_completion','machine_inputs',
        'sensro_inputs','machine_completion_day','machine_inputs_day')->orderby('created_at','desc')->first()->toArray();
        $mutable = Carbon::now()->format('Y-m-d');
        $Statusid = Resource::where('id','>',$data['id'])->wheredate('date','=','2019-03-07')->first();
        
        $data['status']==3?$count['open']++:$count['open'];
        $data['status']==4?$count['turn_off']++:$count['turn_off'];
        $data['status']==15?$count['sensro_inputs']++:$count['sensro_inputs'];

        if($data['orderno']!=$Statusid['orderno']&&$Statusid['id']!=null) {
            $count['machine_completion']=0;
            $count['machine_inputs']=0;
        }else{
            $data['status']==9?$count['machine_completion']++:$count['machine_completion'];
            $data['status']==10?$count['machine_inputs']++:$count['machine_inputs'];
        }

        if(!$mutable) {
            $count['machine_completion_day']=0;
            $count['machine_inputs_day']=0;
        }else{
            $data['status']==9?$count['machine_completion_day']++:$count['machine_completion_day'];
            $data['status']==10?$count['machine_inputs_day']++:$count['machine_inputs_day'];
        }

        // Summary::create($count);
        // dd($count['machine_completion']);
        

        return $count;
    }

    public function create($data)
    {
       return Summary::create($data);
    }

  

}
