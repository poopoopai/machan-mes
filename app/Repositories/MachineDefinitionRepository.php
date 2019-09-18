<?php

namespace App\Repositories;

use App\Entities\MachineDefinition;
use App\Entities\ProcessRouting;
use App\Entities\MachineCategory;

class MachineDefinitionRepository
{
    
    public function getMachineCode($data)
    {
        
        $machine_data = MachineCategory::where('id', $data['machine_category'])->first();
        $aps_code = ProcessRouting::where('aps_id', $data['aps_process_code'])->first();
        
        $machine_id = $data['aps_process_code'].$machine_data['machine_type'];
        $data['machine_category_name'] = $machine_data->machine_name;
        $data['machine_category'] = $machine_data->machine_id;
        $data['machine_id'] = $machine_id;
        $data['process_description'] = $aps_code->process_routing_name;
        
        return $data ;
    }

    public function create($data)
    {
        return MachineDefinition::create($data);
    }

    public function destroy($id)
    {
        return MachineDefinition::destroy($id);
    }
    public function find($id)
    {
        return MachineDefinition::with('Rest')->find($id);
    }
    public function update($id , array $data)
    {
        $machine_definition = MachineDefinition::find($id);

        if(isset($data['machine_category'])){    
            $machine_data = MachineCategory::where('id', $data['machine_category'])->first();
            $data['machine_id'] = $machine_definition['aps_process_code'].$machine_data['machine_type'];
            $data['machine_category_name'] = $machine_data->machine_name;
            $data['machine_category'] = $machine_data->machine_id;
        }
        if(isset($data['aps_process_code'])){
            $aps_code = ProcessRouting::where('aps_id', $data['aps_process_code'])->first();
            $lastsign = str_split($machine_definition['machine_id']);
            $data['machine_id'] = $data['aps_process_code'].$lastsign[sizeof($lastsign)-1];
            $data['process_description'] = $aps_code->process_routing_name;
            $data['aps_process_code'] = $aps_code->aps_id;
        }  
    
        return $machine_definition ? $machine_definition->update($data) : false ;
    }
    public function machineDefinitionIndex($amount)
    {
        return MachineDefinition::paginate($amount);
    }

}