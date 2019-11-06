<?php

namespace App\Http\Controllers;

use App\Repositories\MachineCategoryRepository;

class MachineCategoryController extends Controller
{
    protected $machineRepo;

    public function __construct(MachineCategoryRepository $machineRepo)
    {
        $this->machineRepo = $machineRepo;
    }

    public function index()
    {
        return view('system/machinecategory');
    }

    public function create()
    {
        return view('system/createmachinecategory');
    }

    public function store()
    {

        $data = request()->only(
            'machine_name',
            'type',
            'auto',
            'interface',
            'break_time',
            'data_integration',
            'machine_type',
            'auto_up',
            'auto_down',
            'arrange',
            'auto_arrange',
            'auto_change',
            'auto_pay',
            'auto_finish'
        );

        $machinedata = $this->machineRepo->identifymachine($data);

        $this->machineRepo->create($machinedata);

        return redirect()->route('machine-category.index');
    }

    public function edit($id)
    {
        $data = $this->machineRepo->find($id);

        if (!$data) {
            return redirect()->route('machine-category.index');
        }

        return view('system/editmachinecategory', ['datas' => $data]);
    }

    public function update($id)
    {

        $data = request()->only(
            'machine_name',
            'type',
            'auto',
            'interface',
            'break_time',
            'data_integration',
            'machine_type',
            'auto_up',
            'auto_down',
            'arrange',
            'auto_arrange',
            'auto_change',
            'auto_pay',
            'auto_finish'
        );

        $findmachinedata = $this->machineRepo->identifymachine($data);

        $this->machineRepo->update($id, $findmachinedata);

        return redirect()->route('machine-category.index');
    }

    public function destroy($id)
    {
        $machine = $this->machineRepo->destroy($id);

        if ($machine) {
            return redirect()->route('machine-category.index');
        }

        return back();
    }

    public function getMachineId()
    {
        $data = $this->machineRepo->getmachines();
        return response()->json($data);
    }
    public function machineCategoryIndex()
    {
        return $this->machineRepo->machineCategoryIndex(request()->amount);
    }
}
