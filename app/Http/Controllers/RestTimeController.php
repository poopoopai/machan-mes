<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RestTimeRepository;

class RestTimeController extends Controller
{
    public function __construct(RestTimeRepository $restRepo)
    {
        $this->restRepo = $restRepo;
    }

    public function index()
    {
        return view('system/resttime');
    }

    public function create()
    {
        return view('system/createresttime');
    }

    public function store(Request $request)
    {
        $data = $this->restRepo->create(request()->only('work_name', 'work_type', 'rest_remark', 'type', 'rest_time_start', 'rest_time_end'));
        return redirect()->route('rest-time.index');
    }    
    
    public function edit($id)
    {
        
        $data = $this->restRepo->find($id);
     
        return view('system/editresttime', ['result' => $data]);
    }

    public function update(Request $request, $id)
    {
        $data = request()->only('rest_name', 'work_type');
        $this->restRepo->update($data, $id);
        dd(123);
        return back();
    }

    public function destroy($id)
    {
        $this->restRepo->destroy($id);
        return back();
    }
    public function getRestTime()
    {
        $result = $this->restRepo->restTimeData(request()->amount);
        return response()->json($result);
    }

    public function updateData($restId, $id)
    {
        $data = request()->only('start', 'end', 'type', 'remark');
        $result = $this->restRepo->updateData($data, $id, $restId);
        if (!$result) {
            return back()->with('message', 'The time has repeated');
        }
        return back();
    }

    public function deleteData($id, $restId)
    {
        $this->restRepo->deleteData($id, $restId);
        return back();
    }
}
