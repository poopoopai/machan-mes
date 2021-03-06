<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyCalendarRepository;

class CalendarController extends Controller
{
    protected $comRepo;

    public function __construct(CompanyCalendarRepository $comRepo)
    {
        $this->comRepo = $comRepo;
    }

    public function getCalnedar()
    {
        $data = request(['year', 'month']);
        $result = $this->comRepo->show($data);
        return response()->json($result);
    }

    public function calendar()
    {
        $data = request(['date', 'workId', 'status']);
        if ($data['status'] == '1' || $data['status'] == '2' || $data['status'] == '3') {
            $result = $this->comRepo->create($data);
        } else {
            return response()->json(['status' => 'error'], 422);
        }
        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function yearCalendar()
    {
        return view('calendar/yearcalendar');
    }

    public function fullCalendar()
    {
        return view('calendar/fullcalendar', ['year' => request()->year, 'month' => request()->month]);
    }
}
