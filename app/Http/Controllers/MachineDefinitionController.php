<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MachineDefinitionRepository;


class MachineDefinitionController extends Controller
{
    protected $machineDef;

    public function __construct(MachineDefinitionRepository $machineDefinition)
    {
        $this->machineDef = $machineDefinition;
    }

    public function index()
    {
        $data = $this->machineDef->page();
       
        return view("system/machinedefinition", ['datas' => $data]);
    }

    public function create()
    {
        return view("system/createmachinedefinition");
    }

    public function store()
    {
        $getMachineId = $this->machineDef->getMachineCode(request()->all());
        
        $data = $this->machineDef->create($getMachineId);
        
        return redirect()->route('machine-definition.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = $this->machineDef->find($id);

        $getRest = $this->machineDef->getRest();

        if (!$data) {
            return redirect()->route('machine-definition.index');
        }

        return view('system/editmachinedefinition', ['datas' => $data , 'getRest' => $getRest]);
    }

    public function update($id)
    {
        $this->machineDef->update($id, request()->all());

        return redirect()->route('machine-definition.index');
    }

    public function destroy($id)
    {
        $machinedef = $this->machineDef->destroy($id);

        if($machinedef){
            return redirect()->route('machine-definition.index');
        }

        return back();
    }
}
