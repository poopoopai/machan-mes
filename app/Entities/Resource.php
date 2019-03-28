<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['id','OrderNo','Status','Code'];
}
