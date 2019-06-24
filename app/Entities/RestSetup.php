<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class RestSetup extends Model
{
    protected $fillable = ['start', 'end', 'rest_id', 'remark', 'type'];
}
