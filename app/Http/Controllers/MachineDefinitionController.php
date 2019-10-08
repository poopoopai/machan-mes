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
        $getMachineId = $this->machineRepo->getMachineCode(request()->all());
        
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
        $this->machineRepo->update($id, request()->all());

        return redirect()->route('machine-definition.index');
    }

    public function destroy($id)
    {
        $machinedef = $this->machineRepo->destroy($id);

        if($machinedef){
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
