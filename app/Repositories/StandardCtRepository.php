<?php

namespace App\Repositories;

use App\Entities\StandardCt;

class StandardCtRepository 
{

    public function create($data)
    {
        $data['standard_processing'] = (int)$data['standard_ct'] + (int)$data['standard_updown'];

        return StandardCt::updateOrCreate(
            [
                'machinedefinition_id' => $data['machinedefinition_id'],
                'orderno' => $data['orderno'] 
            ],
            [
                'standard_ct' => $data['standard_ct'],
                'standard_updown'=> $data['standard_updown'],
                'standard_processing'=> $data['standard_processing']
            ]
        );
    }

    public function destroy($id)
    {
        return StandardCt::destroy($id);
    }

    public function find($id)
    {
        return StandardCt::with('MachineDefinition')->find($id);
    }

    public function update($id , array $data)
    {
        $Machine = StandardCt::find($id);

        if($Machine){
            $data['standard_processing'] = (int)$data['standard_ct'] + (int)$data['standard_updown'];
            return $Machine->update($data);
        }
    }
    public function ProcessingTimeIndex($amount)
    {
        return StandardCt::with('MachineDefinition')->paginate($amount);
    }
}

?>