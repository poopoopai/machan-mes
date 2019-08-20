<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ErrorCode extends Model
{
    protected $fillable = ['code', 'message', 'machine_type', 'group'];

    public function resource()
        {
            return $this->hasMany('App\Entities\Resource','code');
        }
}
