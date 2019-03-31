<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\MachineCategory;
use App\Http\Repositories\MachineCategoryRepository;

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
    // dd($request);
        $data = request()->only(
            'machine_name','type', 'auto', 'auto_up', 'auto_down','arrange',
            'auto_arrange','auto_change','auto_pay','auto_finish','interface','break_time'
            );    

             MachineCategory::create($data);

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
        // $this->machineRepo->interface($data);
        // dd($data);
        MachineCategory::find($id)->update($data);
        

        return redirect()->route('machine-category.index');
    }

    public function destroy($id)
    {
        $data = MachineCategory::destroy($id);

        return redirect()->route('machine-category.index');
    }
}
