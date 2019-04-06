<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    
    protected $fillable = ['id','orderno','status_id','code']; 

    // public function mainprogram()
    // {
    //     return $this->belongsTo('App\Entities\MainProgram');
    // }
}
