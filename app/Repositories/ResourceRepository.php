<?php

namespace App\Repositories;

use App\Entities\Resource;

class ResourceRepository
{
    public function index($data)
    {
        return Resource::where('org_id', $data)
            ->get(['id', 'resource_name', 'workcenter_name', 'aps_id'])
            ->each(function ($data, $key ) {
                if ((int) substr($data->aps_id, 3, 1) !== 0) {
                    $data->resource_name = $data->resource_name.'('.explode('-', $data->workcenter_name)[1].')';
                }
            });
    }
}
