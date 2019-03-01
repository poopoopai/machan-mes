<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProcessCalendarRepository;

class ProcessCalendarController extends Controller
{
    protected $proRepo;

    public function __construct(ProcessCalendarRepository $proRepo)
    {
        $this->proRepo = $proRepo;
    }

    public function processCalendar()
    {
        return view('calendar/processcalendar');
    }

    public function showProcessCalendar()
    {
        $year = date('Y', strtotime(request()->date));
        $month  = date('n', strtotime(request()->date));
        $resourceId =  request()->id;
        return view('calendar/adjustprocesscalendar', compact('year', 'month', 'resourceId'));
    }

    public function adjustProcessCalendar()
    {
        $data = request()->id;
        $result = $this->proRepo->index($data);
        return response()->json($result);
    }

    public function workCalendar()
    {
        $data = request()->all();
        if ($data['status'] == '2' || $data['status'] == '3') {
            $data['start'] = $data['end'] = NULL;
            $result = $this->proRepo->create($data);
        } elseif ($data['status'] == '1') {
            $result = $this->proRepo->create($data);
        } else {
            return response()->json(['status' => 'error'], 422);
        }
        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function processCalendarData()
    {
        $data = request()->all();
        $result = $this->proRepo->show($data);
        return response()->json($result);
    }

}
