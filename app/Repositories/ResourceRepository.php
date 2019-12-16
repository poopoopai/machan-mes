<?php

namespace App\Repositories;

use App\Entities\Resource;

class ResourceRepository
{
    public function data()
    {
        return Resource::where('flag', 0)->orderby('time')->get();
    }

    public function updateflag($data)
    {
        return Resource::where('id', $data->id)->update(['flag' => 1]);
    }
}
