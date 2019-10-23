<?php

namespace App\Repositories;

use App\Entities\Resource;
use App\Entities\ErrorCode;
use App\Entities\StandardCt;
use App\Entities\Summary;

class ResourceRepository
{
    public function abnormal($data, $status)
    {
       
        $Statusid = Resource::where('id', '>', $data['id'])->wheredate('date', $data['date'])->first(); //判斷後面的id date要等於當日
            $summary = '0';
        
        if ($data['status_id'] == '9' || $data['status_id'] == '10'||$data['status_id'] == '3' || $data['status_id'] == '15' || $data['status_id'] == '16') {
            
                if($data['orderno'] != $Statusid['orderno'] && $Statusid['id'] != null) {
                    $summary = "換線";
                }
                else{
                    $summary = '0';
                }
        } elseif($data['code'] == 0) {
            $summary = $status->description;         
        } elseif($data['code'] != 0){
            $summary = ErrorCode::where('machine_type', $status->type)->where('code', $data['code'])->first();
             return $summary->message;
        } else{
            $summary = '0';
        }

        return $summary;
           
    }
    public function message($data, $status)
    {
        
        $message = '0';
    
        $status->abnormal == '0' ? $message = $status->description : $message = $status->abnormal;
        
        if($data['status_id'] == '3'){
            $message = '開機';
        } elseif($data['status_id'] == '4'){
            $message = '關機';
        } elseif($data['status_id'] == '20' || $data['status_id'] == '21'){
            $message = '換料';
        }
         
       return  $message;
    }
    public function completion($data, $message, $machine)
    {
   
        $Statusid = Resource::where('id', '>', $data['id'])->first();
        $comletion = 0;
        
        if($Statusid){
            if ($machine == '捲料機1'){
                if ($data['status_id'] == 9 || $data['status_id'] == 10 || $data['status_id'] == 15 || $data['status_id'] == 16) {
                    
                    if($data['status_id'] == 9){
                        $Statusid->status_id - $data['status_id'] == 1 ? $comletion = '正常生產' : $comletion = '不正常';
                    } else{
                        if($data['status_id'] == 10){
                            $Statusid->status_id - $data['status_id'] == 5 ? $comletion = '正常生產' : $comletion = '不正常';
                        } else{
                            if($data['status_id'] == 16){
                                $Statusid->status_id - $data['status_id'] == 6 ? $comletion = '正常生產' : $comletion = '不正常';
                            } else{
                                if($data['status_id'] == 15){
                                    $Statusid->status_id - $data['status_id'] == 6 ? $comletion = '正常生產' : $comletion = '不正常';
                                } else{
                                    if($data['status_id'] == 3 ||$data['status_id'] == 4 ||$data['status_id'] == 20 ||$data['status_id'] == 21){
                                        $comletion = $message;
                                    } else{
                                        $comletion = '異常';
                                    }
                                }
                            }
                        }        
                    }           
                } else{
                    $comletion = '異常';
                }
            } else{
                if($data['status_id'] == 10){
                    $comletion = '正常生產';
                }else{
                    $comletion = '異常';
                }
            }
        } else{// 最後一筆
            $comletion = '異常';
        }  
            return $comletion ;    
        }
    
    public function data()
    {
        return Resource::where('flag', 0)->get();
    }

    public function updateflag($data)
    { 
        return Resource::where('id', $data->id)->update(['flag'=>1]); 
    }

    public function machine($data)
    {
        $machine = '';

        if ($data->orderno === null) {
            $machineid = Summary::where('open', '1')->first();
            if($machineid){
                $find = Resource::where('id', $machineid->resources_id)->first();
                $order = StandardCt::where('orderno', $find->orderno)->with('MachineDefinition')->first();
                $machine = $order->MachineDefinition->machine_name;
            }
        } else{
            $order = StandardCt::where('orderno', $data->orderno)->with('MachineDefinition')->first();
            if($order){
                $machine = $order->MachineDefinition->machine_name;
            } else{
                $machine = null; // 如果沒有找到料號
            }
            
        }
       
        return $machine;
    }
}
