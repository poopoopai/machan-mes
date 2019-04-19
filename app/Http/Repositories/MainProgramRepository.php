<?php

namespace App\Http\Repositories;

use App\Entities\MainProgram;

class MainProgramRepository
{
    public function description($data)
    {
        return MainProgram::select('description','type')
            ->where('status', $data['status'])
            ->first();
    }
}