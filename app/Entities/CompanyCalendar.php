<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class CompanyCalendar extends Model
{
    protected $fillable = ['date', 'start', 'end', 'status'];
}
