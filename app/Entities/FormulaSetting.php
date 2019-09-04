<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class FormulaSetting extends Model
{
    protected $fillable = ['variable', 'variable_type', 'formula', 'total'];
}
