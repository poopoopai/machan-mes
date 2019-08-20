<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ApsProcessCode extends Model
{
    protected $fillable = [ 'aps_process_code', 'process_description'];
}
