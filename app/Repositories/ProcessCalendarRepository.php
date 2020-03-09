<?php

namespace App\Repositories;

use App\Entities\ProcessRouting;
use App\Entities\ProcessCalendar;
use App\Entities\MachineDefinition;
class ProcessCalendarRepository
{
    public function index($data)
    {
        $process_routing = ProcessRouting::where('id' , $data['id'] )->first();

        return MachineDefinition::where('aps_process_code', $process_routing->aps_id)->get();
    }

    public function create(array $data)
    {
        return ProcessCalendar::updateOrCreate(
            ['date' => $data['date'], 'resource_id' => $data['resource_id']],
            [
                'work_type_id' => $data['workId'],
                'status' => $data['status']
            ]
        )->load('setupShift');
    }

    public function show(array $data)
    {
        return ProcessCalendar::whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('resource_id', $data['resourceId'])
            ->with('setupShift')
            ->get();
    }
}
