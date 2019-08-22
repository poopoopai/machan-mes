<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class MachineDefinition extends Model
{
    protected $fillable = 
    [ 
        'machine_id',
        'machine_name',
        'machine_category',
        'machine_category_name',
        'aps_process_code',
        'process_description',
        'api_integration',
        'api_integration_name',
        'group_setting',
        'oee_assign',
        'class_assign',
        'production_time',
        'change_line_time',
    ];

    public function Rest()
    {
        return $this->hasOne('App\Entities\RestGroup', 'id', 'class_assign');
    }
}
