<?php

namespace App\Http\Repositories;

use App\Entities\MachineCategory;

class MachineCategoryRepository
{
    public function index()
    {
        return  MachineCategory::select('id', 'machine_id', 'machine_name', 'type', 'auto', 'interface')->paginate(100);
    }

    public function update($id, array $data)
    {
        $Machine = MachineCategory::find($id);

        if ($Machine) {
            return $Machine->update($data);
        }
        return false;
    }
    public function interface($data)
    {
        switch ($data['interface'])
        {
            case 'A': $data['interface']="可離線生產";
            break;
            case 'B': $data['interface']="人機同步生產";
            break;
            case 'C': $data['interface']="遠端遙控生產";
            break;
            case 'D': $data['interface']="無人化自動生產";
            break;
            case 'E': $data['interface']="人機手動";
            break;     
            default:
            return false;    
        }
        // dd($data);
           return $data;
    }
}
