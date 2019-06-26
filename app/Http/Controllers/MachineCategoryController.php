<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\MachineCategory;
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

    public function store(Request $request)
    {
        
        $data = $this->machineRepo->identify($request->all());

        $result = $this->machineRepo->create($data);
      
        return redirect('machine-category');
    }

    public function edit($id)
    {
        
       $data = $this->machineRepo->find($id);

         if (!$data) 
         {
             return back();
         }
        return view('system/edit/editmachinecategory',[ 'data' => $data]);
    }

    public function update(Request $request, $id)
    {
 
        $find = $this->machineRepo->identify($request->all());
       
        $this->machineRepo->find($id)->update($find);

        return redirect('machine-category');
    }

    public function destroy($id)
    {
        $this->machineRepo->destroy($id);

        return redirect('machine-category');
    }
}
