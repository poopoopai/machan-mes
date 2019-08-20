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
        $data = $this->machineRepo->page();
       
        return view('system/machinecategory', ['datas' => $data]);
    }

    public function create()
    {
        return view('system/createmachinecategory');
    }

    public function store()
    {
        $data = $this->machineRepo->identify(request()->all());

        $result = $this->machineRepo->create($data);
      
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
        $find = $this->machineRepo->identify(request()->all());
       
        $this->machineRepo->update($id, $find);

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
        $data = $this->machineRepo->getAll();
        return response()->json($data);
    }
}
