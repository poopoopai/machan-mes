<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class MainProgramCode extends Model
{
    protected $fillable = ['status', 'description','type','codeX','group'];

    public function errorCode()
    {
        return $this->hasOne('App\Entities\ErrorCode', 'group', 'group');
    }
}
