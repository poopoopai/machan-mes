<?php

namespace App\Http\Controllers;

use App\Repositories\ApsProcessCodeRepository;

class ApsProcessCodeController extends Controller
{
    protected $aps_process;

    public function __construct(ApsProcessCodeRepository $aps_processcode)
    {
        $this->aps_process = $aps_processcode;
    }
    
    public function index()
    {
        $data = $this->aps_process->page();
        
        return view('system/apsprocesscode', ['datas' => $data]);
    }

   
    public function create()
    {
        return view('system/createapsprocesscode');
    }

    
    public function store()
    { 
        $data = $this->aps_process->create(request()->only("aps_process_code" , "process_description" ));
   
        return redirect()->route('aps-processcode.index');
    }

   
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = $this->aps_process->find($id);

        if (!$data) {
            return redirect()->route('aps-processcode.index');
        }
     
        return view('system/editapsprocesscode', ['datas' => $data]);
    }

    public function update($id)
    {
        $aps_process = $this->aps_process->update($id, request()->only('aps_process_code','process_description'));

        return redirect()->route('aps-processcode.index');
    }

   
    public function destroy($id)
    {
        $aps_process = $this->aps_process->destroy($id);

        if($aps_process){
            return redirect()->route('aps-processcode.index');
        }

        return back();
    }
    public function getApsData()
    {
        $data = $this->aps_process->getData();

        return response()->json($data);
    }
}
