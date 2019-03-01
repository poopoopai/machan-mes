<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ProcessCalendar extends Model
{
    protected $fillable = ['resource_id', 'date', 'start', 'end', 'status'];
}
