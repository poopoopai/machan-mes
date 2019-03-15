<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    protected $fillable = ['id','port_number','program-code','error-code'];
}
