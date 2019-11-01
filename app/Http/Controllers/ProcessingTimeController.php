<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StandardCtRepository;

class ProcessingTimeController extends Controller
{
    protected $standctRepo;

    public function __construct(StandardCtRepository $standctRepo)
    {
        $this->standctRepo = $standctRepo;
    }


    public function index()
    {
        return view('system/processingtime');
    }

   
    public function create()
    {
        return view('system/createprocessingtime');
    }

    
    public function store()
    {
        return redirect()->route('processing-time.index');
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $data = $this->standctRepo->find($id);
       
        if (!$data) {
            return redirect()->route('processing-time.index');
        }
        return view('system/editprocessingtime', ['datas' => $data]);
    }

    public function update($id)
    {
       
        $standct = $this->standctRepo->update($id, request()->all());

        if(is_null($standct)){
            return response()->json(['status' => 'error', 'message' => 'Message Not Found'], 404);
        } 
        
        return redirect()->route('processing-time.index');

    }

    public function destroy($id)
    {
        $standct = $this->standctRepo->destroy($id);

        if($standct){
            return redirect()->route('processing-time.index');
        }

        return back();
    }
    
    public function ProcessingTimeIndex()
    {
        return $this->standctRepo->ProcessingTimeIndex(request()->amount);
    }
}
