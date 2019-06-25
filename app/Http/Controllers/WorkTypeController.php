<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\WorkTypeRepository;

class WorkTypeController extends Controller
{
    protected $workRepo;

    public function __construct(WorkTypeRepository $workRepo)
    {
        $this->workRepo = $workRepo;
    }

    public function index()
    {
        return view('system/worktype');
    }

    public function create()
    {
        return view('system/createworktype');
    }

    public function store(Request $request)
    {
        $result = $this->workRepo->create(request()->only('work_type', 'work_time_start', 'work_time_end', 'rest_id', 'work_name'));
        if (!$result) {
            return back()->with('message', 'The time has repeated');
        }
        return redirect()->route('work-type.index');
    }

    public function edit($id)
    {
        $result = $this->workRepo->find($id);
        return view('system/editworktype', ['result' => $result]);
    }

    public function update(Request $request, $id)
    {
        $data = request()->only('rest_id', 'work_type', 'work_on', 'work_off', 'name');
        $this->workRepo->update($data, $id);
        return redirect()->route('work-type.index');
    }

    public function destroy($id)
    {
        $this->workRepo->destroy($id);
        return back();
    }
    public function getWorkTypeData()
    {
        $result = $this->workRepo->getWorkTypeData(request()->amount);
        return response()->json($result);
    }

    public function getRestGroup()
    {
        $data = request()->value;
        $result = $this->workRepo->getRestGroup($data);
        return response()->json($result);
    }
}
