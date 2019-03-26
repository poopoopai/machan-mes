<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    protected $fillable = ['port_number','program_code','error_code'];
}
