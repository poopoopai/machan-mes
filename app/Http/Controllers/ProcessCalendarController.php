<?php

namespace App\Http\Controllers;

use App\Repositories\ProcessCalendarRepository;
use App\Entities\ProcessCalendar;
use App\Entities\CompanyCalendar;
use App\Entities\RestGroup;
use Carbon\Carbon;

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
        $orgId = request()->org_id;
        return view('calendar/adjustprocesscalendar', compact('year', 'month', 'resourceId', 'orgId'));
    }

    public function adjustProcessCalendar()
    {
        $data = request(['id', 'org_id']);
        
        $result = $this->proRepo->index($data);
        return response()->json($result);
    }

    public function workCalendar()
    {
        $data = request(['date', 'resource_id', 'status', 'workId']);
        if ($data['status'] == '1' || $data['status'] == '2' || $data['status'] == '3') {
            $result = $this->proRepo->create($data);
        } else {
            return response()->json(['status' => 'error'], 422);
        }
        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function processCalendarData()
    {
        $data = request(['year', 'month', 'resourceId']);
        $result = $this->proRepo->show($data);
        return response()->json($result);
    }

    public function workData()
    {
        $data = request(['year', 'month', 'resourceId']);
        $workDays = 0;
        $adjustWeek = 0;
        $workTime = 0;
        $workOverDays = 0;
        $workOverTime = 0;
        $workTimeInfo = 0;
        $month = Carbon::create($data['year'], $data['month']);
        
        $daysInMonth = Carbon::parse($month)->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $process = ProcessCalendar::where('resource_id', $data['resourceId'])
                ->whereYear('date', $data['year'])
                ->whereMonth('date', $data['month'])
                ->whereDay('date', $i)
                ->with('setupShift')
                ->first();
            $company = CompanyCalendar::whereYear('date', $data['year'])
                ->whereMonth('date', $data['month'])
                ->whereDay('date', $i)
                ->with('setupShift')
                ->first();
            if ($process != null) {
                switch ($process->status) {
                    case '1':
                        $workDays++;
                        $workTime += $this->getWorkTime($process);     
                        $holiday = $this->checkDay($data, $i);
                        if (!$holiday) {
                            $workTimeInfo = $this->getWorkTime($process);
                            if (480 - $workTimeInfo < 0) {
                                $workOverDays++;  
                                $workOverTime += abs(480 - $workTimeInfo);
                            }
                        } else {
                            $workOverDays++;
                            $workTimeInfo = $this->getWorkTime($process);
                            $workOverTime += $workTimeInfo;
                        }
                        break;
                    case '2':
                        $holiday = $this->checkDay($data, $i);
                        if (!$holiday) {
                            $adjustWeek++;
                        }
                        break;
                    case '3':
                        $holiday = $this->checkDay($data, $i);
                        if (!$holiday) {
                            $adjustWeek++;
                        }
                        break;
                }
            } elseif ($company != null) {
                switch ($company->status) {
                    case '1':
                        $workDays++;
                        $workTime += $this->getWorkTime($company);
                        
                        $holiday = $this->checkDay($data, $i);
                        if (!$holiday) {
                            $workTimeInfo = $this->getWorkTime($company);
                            if (480 - $workTimeInfo < 0) {
                                $workOverDays++;
                                $workOverTime += abs(480 - $workTimeInfo);
                            }
                        } else {
                            $workOverDays++;
                            $workTimeInfo = $this->getWorkTime($company);
                            $workOverTime += $workTimeInfo;
                        }
                        break;
                    case '2':
                        $holiday = $this->checkDay($data, $i);
                        if (!$holiday) {
                            $adjustWeek++;
                        }
                        break;
                    case '3':
                        $holiday = $this->checkDay($data, $i);
                        if (!$holiday) {
                            $adjustWeek++;
                        }
                        break;
                }
            } else {
                $weekDay = Carbon::create($data['year'], $data['month'], $i)->format('D');
                if ($weekDay !== 'Sat' && $weekDay !== 'Sun') {
                    $workDays++;
                    $workTime += 480;
                }
            }
        }
        $workTime = round($workTime / 60, 1);
        $workOverTime = round($workOverTime / 60, 1);

        return response()->json([
            'workDays' => $workDays,
            'workTime' => $workTime,
            'adjustWeek' => $adjustWeek,
            'workOverTime' => $workOverTime,
            'workOverDays' => $workOverDays
        ]);
    }

    private function getCalendarData(array $data, $days, $status)
    {
        return ProcessCalendar::whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('resource_id', $data['resourceId'])
            ->where('status', $status)
            ->count();
    }

    private function checkDay(array $data, $i)
    {
        $weekDay = Carbon::create($data['year'], $data['month'], $i)->format('D');
        if ($weekDay !== 'Sat' && $weekDay !== 'Sun') {
            return false;
        }
        return true;
    }

    private function getWorkTime($data)
    {
        
        $rest_total_time = 0;
        $times = 0;
        $work_on = $data->setupShift->work_on;
        $work_off = $data->setupShift->work_off;
        $rest_group = $data->setupShift->rest_group;
        $rest_setup = RestGroup::where('id', $rest_group)->first();
        $rest_time = $rest_setup->restSetup;
        foreach ($rest_time as $key => $time) {
            $rest_total_time += Carbon::parse($time->start)->diffInMinutes(Carbon::parse($time->end));
        }
        
        if($work_off === "00:00:00") {
            $work_off = "24:00:00";
        }
        $work_total_time = Carbon::parse($work_on)->diffInMinutes(Carbon::parse($work_off));
        
        return $times += ($work_total_time - $rest_total_time);
    }

}
