<?php

namespace App\Http\Repositories;

use App\Entities\Resource;

class ResourceRepository
{
    public function index($data)
    {
        return Resource::select('id','machine_name','type', 'auto', 'auto_up','interface')->paginate(10);
    }

    public function create($data)
    {
        
    }

}