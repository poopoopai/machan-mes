<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class MainProgram extends Model
{
    
    protected $fillable = ['status', 'description', 'type', 'codeX', 'group'];

    public function resources()
        {
            return $this->hasMany('App\Entities\Resource', 'status_id', 'status');
        }
}
