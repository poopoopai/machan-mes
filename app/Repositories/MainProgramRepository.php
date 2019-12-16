<?php

namespace App\Repositories;

use App\Entities\MainProgram;
use App\Entities\StandardCt;

class MainProgramRepository
{
    public function description($data)
    {
        return MainProgram::select('description','type')
            ->where('status', $data['status_id'])
            ->first();
    }

    public function findOrderno($data)
    {
        return StandardCt::where('orderno', $data->orderno)->with('MachineDefinition')->first();
    }
}