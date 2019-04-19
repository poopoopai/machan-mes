<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['machine_name','machine_id','orderno','status_id','code','date','time','flag']; 
}
