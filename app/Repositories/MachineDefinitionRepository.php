<?php

namespace App\Repositories;

use App\Entities\MachineDefinition;
use App\Entities\ApsProcessCode;

class MachineDefinitionRepository
{
    public function page()
    {
        return MachineDefinition::select(
            'id',
            'machine_id',
            'machine_name',
            'machine_category',
            'machine_category_name',
            'aps_process_code',
            'process_description',
            'api_integration',
            'api_integration_name',
            'group_setting',
            'oee_assign',
            'class_assign',
            'production_time',
            'change_line_time'
        )->paginate(100);
    }

    public function getMachineCode($data)
    {
        
        $code = str_split($data['aps_process_code'], 2);
        
        $machine_id = $code[1].$code[0];

        $type = explode('+', $data['machine_category']);

        $machine_id = $machine_id.$type[1];

        $aps_code = ApsProcessCode::where('aps_process_code', $data['aps_process_code'])->first();
       
        $data['machine_id'] = $machine_id;
        $data['machine_category'] = $type[0];
        $data['machine_category_name'] = $type[2];
        $data['process_description'] = $aps_code->process_description;

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
        return MachineDefinition::find($id);
    }
    public function getRest()
    {
        return MachineDefinition::with('Rest')->first();
    }
    public function update($id , array $data)
    {
        $machine_definition = ApsProcessCode::find($id);

        return $machine_definition ? $machine_definition->update($data) : false ;
    }




}