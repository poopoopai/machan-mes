<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SetupShift extends Model
{
    protected $fillable = ['name', 'type', 'work_on', 'work_off', 'rest_group', 'total_rest_time'];
}
