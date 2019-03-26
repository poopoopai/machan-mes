<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class MachineCategory extends Model
{
    protected $fillable = [
        'no',
        'machine_id',
        'machine_name',
        'tpye',
        'auto',
        'auto_up',
        'auto_down',
        'arrange',
        'auto_arrange',
        'auto_change',
        'auto_pay',
        'auto_finish',
        'interface',
        'data_integration',
        'break_time',
        'machine_type',
        'remark',
    ];
}
