<?php

namespace App\Repositories;

use App\Entities\ApsProcessCode;

class ApsProcessCodeRepository
{
    public function page()
    {
        return  ApsProcessCode::select('id', 'aps_process_code', 'process_description')->paginate(100);
    }

    public function create($data)
    {
        return ApsProcessCode::create($data);
    }

    public function find($id)
    {
        return ApsProcessCode::find($id);
    }

    public function update($id , array $data)
    {
        $aps_process = ApsProcessCode::find($id);

        return $aps_process ? $aps_process->update($data) : false ;
    }

    public function destroy($id)
    {
        return ApsProcessCode::destroy($id);
    }

    public function getData()
    {
        return ApsProcessCode::get();
    }

}