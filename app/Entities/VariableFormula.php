<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class VariableFormula extends Model
{
    protected  $fillable = [ 'variable' , 'variablename' , 'remark'];
}
