<?php

namespace App\Http\Repositories;

use App\Entities\Resource;
use App\Entities\ErrorCode;
use Carbon\Carbon;
class ResourceRepository
{
    public function abnormal($data,$status)
    {
        
       $mutable = Carbon::now()->format('Y-m-d');
       
        $Statusid = Resource::where('id','>',$data['id'])->wheredate('date','=','2019-03-07')->first(); //判斷後面的id
       
        $summary = '0';
        //   dd($data['orderno']);
        
        if ($data['status']=='9'||$data['status']=='10'||$data['status']=='3'||$data['status']=='15'||$data['status']=='16') {
            
                if($data['orderno']!=$Statusid['orderno']&&$Statusid['id']!=null) {
                    $summary = "換線";
                }
                else{
                    $summary = '0';
                }

        } elseif($data['code']==0) {
            $summary = $status['description'];
        } elseif($data['code']!=0){
            $summary = ErrorCode::with('resources')->where('machine_type',$status['type'])->where('code',$data['code'])->first();
             return $summary->message;
        } else{
            $summary = '0';
        }
       
        if($summary==null){
            return response()->json(['status' => 'error', 'data' => 'Data Not Found'], 403);
        }else{
            return $summary;
        }
        
    }
    public function message($data,$status)
    {
        dd($status);
        $message = '0';
        if($data['status']=='3'){
            $message = '開機';
        }elseif($data['status']=='4'){
            $message = '關機';
        }elseif($data['status']=='20'&& $data['status']=='21'){
            $message = '換料';
        }
    }
  

}