<?php

namespace App\Repositories;

use App\Entities\CompanyCalendar;

class CompanyCalendarRepository
{
    public function create(array $data)
    {
        return CompanyCalendar::updateOrCreate(
            ['date' => $data['date']],
            [
                'work_type_id' => $data['workId'],
                'status' => $data['status']
            ]
        )->load('setupShift');
    }

    public function show(array $data)
    {
        return CompanyCalendar::whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->with('setupShift')
            ->get();
    }
}