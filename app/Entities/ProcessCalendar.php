<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ProcessCalendar extends Model
{
    protected $fillable = ['resource_id', 'date', 'work_type_id', 'status'];

    public function setupShift()
    {
        return $this->belongsTo('App\Entities\SetupShift', 'work_type_id');
    }
}
