<?php

namespace App\Repositories;

use App\Entities\MachineCategory;

class MachineCategoryRepository
{
    public function page()
    {
        return  MachineCategory::select('id', 'machine_id', 'machine_name', 'type', 'auto', 'interface')->paginate(100);
    }

    public function create($data)
    {
        return MachineCategory::create($data);
    }

    public function find($id)
    {
        return  MachineCategory::find($id);
    }

    public function update($id , array $data)
    {
        $Machine = MachineCategory::find($id);

        return $Machine ? $Machine->update($data) : false ;
    }

    public function destroy($id)
    {
        return MachineCategory::destroy($id);
    }

    public function getAll()
    {
        return  MachineCategory::get();
    }

    public function identify($data){
  
        $data['machine_id'] = $data['auto'];
        
        if(($data['auto_up'] && $data['auto_down'])==1)
        {
            $change ='L';  
            $data['machine_id'] = $data['machine_id'].$change; 
        }
        elseif(($data['auto_up'] && $data['auto_down'])==0)
        {
            $change ='U';  
            $data['machine_id'] = $data['machine_id'].$change;
        }
   
        switch ($data['type'])
        {
            case 'SS': 
                $change ='S';
                $data['machine_id'] = $data['machine_id'].$change;
                break;
            case 'SM':   
                $change ='M';
                $data['machine_id'] = $data['machine_id'].$change;
                break;
            case 'MS':  
                $change ='S';
                $data['machine_id'] = $data['machine_id'].$change;
                break;
            case 'MM':   
                $change ='M';
                $data['machine_id'] = $data['machine_id'].$change;
                break; 
            default:
                return false;    
        }
        if(($data['arrange'] || $data['auto_arrange'] || $data['auto_change'] || $data['auto_pay'] || $data['auto_finish']) == 1)
        {
            $data['machine_id'] = $data['machine_id'] .'_';

            $arrange ='p';
            $auto_arrange ='s';
            $auto_change ='t';
            $auto_pay ='f';
            $auto_finish ='d';
          
            $data['machine_id'] =  ($data['arrange'] == "1") ? $data['machine_id'].$arrange : $data['machine_id'];
            $data['machine_id'] =  ($data['auto_arrange'] == "1") ? $data['machine_id'].$auto_arrange : $data['machine_id'];
            $data['machine_id'] =  ($data['auto_change'] == "1") ? $data['machine_id'].$auto_change : $data['machine_id'];
            $data['machine_id'] =  ($data['auto_pay'] == "1") ? $data['machine_id'].$auto_pay : $data['machine_id'];
            $data['machine_id'] =  ($data['auto_finish'] == "1") ? $data['machine_id'].$auto_finish:$data['machine_id'];
            
        }
        
        return $data;

    }
}
