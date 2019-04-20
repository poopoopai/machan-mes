<?php

namespace App\Repositories;

use App\Entities\Resource;
use App\Entities\ProcessCalendar;

class ProcessCalendarRepository
{
    public function index($data)
    {
        $orgID = Resource::find($data)->org_id;
        $apsID = Resource::find($data)->aps_id;
        return Resource::where('org_id', $orgID)
            ->where('aps_id', 'like', substr($apsID, 0, 3).'%')
            ->select('id', 'resource_name', 'workcenter_name', 'aps_id')
            ->get()
            ->each(function ($data, $index) {
                if ((int) substr($data->aps_id, 3, 1) !== 0) {
                    $data->resource_name = $data->resource_name.'('.explode('-', $data->workcenter_name)[1].')';
                }
            });
    }

    public function create(array $data)
    {
        return ProcessCalendar::updateOrCreate(
            ['date' => $data['date'], 'resource_id' => $data['resource_id']],
            $data
        );
    }

    public function show(array $data)
    {
        return ProcessCalendar::whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('resource_id', $data['resourceId'])
            ->get();
    }
}
