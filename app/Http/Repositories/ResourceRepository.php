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
        } else{
            $summary = ErrorCode::with('resources')->where('machine_type',$status['type'])->where('code',$data['code'])->first();        
        }      

        if($summary==null){
            return response()->json(['status' => 'error', 'data' => 'Data Not Found'], 403);
        }else{
            return $summary->message;
        }
        
    }

    public function counts($data)
    {
        dd($data);
    }
   

}