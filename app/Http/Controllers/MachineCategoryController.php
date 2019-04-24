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
        
        $data = $this->machineRepo->index();
        //  dd($data);
        return view('system/machinecategory', ['machineinfo'=>$data]);
    }

    public function create()
    {
        return view('system/createmachinecategory');
    }

    public function store(Request $request)
    {
    //  dd($request);
        $data = request()->only(
            'machine_name','type', 'auto', 'auto_up', 'auto_down','arrange',
            'auto_arrange','auto_change','auto_pay','auto_finish','interface','break_time'
            );    
        $find = $this->machineRepo->identify($data);
        
        MachineCategory::create($find);

        return redirect('machine-category');
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        
       $data = MachineCategory::find($id);

         if (!$data) 
         {
             return back();
         }
        return view('system/edit/editmachinecategory',['data'=>$data]);
    }

    public function update(Request $request, $id)
    {

        $data = request()->only(
        'machine_name','type', 'auto', 'auto_up', 'auto_down','arrange',
        'auto_arrange','auto_change','auto_pay','auto_finish','interface','break_time'
        );
      
        $interface = $this->machineRepo->interface($data);
        $find = $this->machineRepo->identify($interface);
       
        MachineCategory::find($id)->update($find);

        return redirect()->route('machine-category.index');
    }

    public function destroy($id)
    {
        $data = MachineCategory::destroy($id);

        return redirect()->route('machine-category.index');
    }
}
