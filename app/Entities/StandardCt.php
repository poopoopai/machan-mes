<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class StandardCt extends Model
{
    protected $fillable = ['orderno', 'standard_ct', 'standard_updown', 'standard_processing'];

    public function resources()
    {
        return $this->hasMany('App\Entities\Resource', 'orderno');
    }
    
}
