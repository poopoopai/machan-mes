<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class StandardCt extends Model
{
    protected $fillable = ['orderno', 'machinedefinition_id', 'standard_ct', 'standard_updown', 'standard_processing' , 'machine'];

    public function resources()
    {
        return $this->hasMany('App\Entities\Resource', 'orderno');
    }

    public function MachineDefinition()
    {
        return $this->belongsTo('App\Entities\MachineDefinition', 'machinedefinition_id', 'id');
    }
    
}
