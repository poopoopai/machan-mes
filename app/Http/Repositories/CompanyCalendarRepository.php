<?php

namespace App\Http\Repositories;

use App\Entities\CompanyCalendar;

class CompanyCalendarRepository
{
    public function create(array $data)
    {
        return CompanyCalendar::updateOrCreate(
            ['date' => $data['date']],
            $data
        );
    }

    public function show(array $data)
    {
        return CompanyCalendar::whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->get();
    }
}