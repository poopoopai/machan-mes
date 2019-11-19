<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MachineDefinitionRepository;


class MachineDefinitionController extends Controller
{
    protected $machineRepo;

    public function __construct(MachineDefinitionRepository $machineDefinition)
    {
        $this->machineRepo = $machineDefinition;
    }

    public function index()
    {
        return view("system/machinedefinition");
    }

    public function create()
    {
        return view("system/createmachinedefinition");
    }

    public function store()
    {
        $data = request()->only('machine_name', 'machine_category', 'aps_process_code', 'group_setting', 'change_line_time', 'class_assign', 'oee_assign');

        $getMachineId = $this->machineRepo->getMachineCode($data);

        $this->machineRepo->create($getMachineId);

        return redirect()->route('machine-definition.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = $this->machineRepo->find($id);

        if (!$data) {
            return redirect()->route('machine-definition.index');
        }
        return view('system/editmachinedefinition', ['datas' => $data]);
    }

    public function update($id)
    {
        $data = request()->only('machine_name', 'machine_category', 'aps_process_code', 'group_setting', 'change_line_time','oee_assign');
        
        $this->machineRepo->update($id, $data);

        return redirect()->route('machine-definition.index');
    }

    public function destroy($id)
    {
        $machinedef = $this->machineRepo->destroy($id);

        if ($machinedef) {
            return redirect()->route('machine-definition.index');
        }

        return back();
    }

    public function machineDefinitionIndex()
    {
        return $this->machineRepo->machineDefinitionIndex(request()->amount);
    }
    public function getMachineDefinition()
    {
        return $this->machineRepo->getMachineDefinition();
    }
}
