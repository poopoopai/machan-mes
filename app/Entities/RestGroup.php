<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class RestGroup extends Model
{
    protected $fillable = ['rest_name', 'work_type', 'total_rest_time'];

    public function restSetup()
    {
        return $this->hasMany('App\Entities\RestSetup', 'rest_id');
    }

    public function shifts()
    {
        return $this->hasMany('App\Entities\SetupShift', 'rest_group');
    }
}
