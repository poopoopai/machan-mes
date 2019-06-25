<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SetupShift extends Model
{
    protected $fillable = ['name', 'type', 'work_on', 'work_off', 'rest_group', 'total_rest_time'];

    public function relatedCompany()
    {
        return $this->hasMany('App\Entities\CompanyCalendar', 'work_type_id');
    }

    public function relatedProcess()
    {
        return $this->hasMany('App\Entities\ProcessCalendar', 'work_type_id');
    }
}
