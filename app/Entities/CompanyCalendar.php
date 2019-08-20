<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class CompanyCalendar extends Model
{
    protected $fillable = ['date', 'work_type_id', 'status'];

    public function setupShift()
    {
        return $this->belongsTo('App\Entities\SetupShift', 'work_type_id');
    }
}
