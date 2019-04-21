<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    protected $fillable = 
    [
        'description',
        'type',
        'abnormal',
        'serial_number',
        'serial_number_day',
        'open',
        'turn_off',
        'time',
        'machine_completion',
        'machine_inputs',
        'machine_completion_day',
        'machine_inputs_day',
        'sensro_inputs',
        'break',
        'break_time',
        'message_status',
        'down_time',
        'completion_status',
        'total_processing_time',
        'second_completion',
        'manufacturing_status',
        'processing_start_time',
        'processing_completion_time',
        'working_time',
        'roll_t',
        'second_t',
        'ct_processing_time',
        'restart_count',
        'restop_count',
        'refueling_start',
        'refueling_end',
        'refueling_time',
        'refueler_time',
        'aggregate_start',
        'aggregate_end',
        'aggregate_time',
        'collector_time',
        'uat_h_36_233',
        'uat_h_36_75',
        'uat_h_36_154',
        'standard_uat_h_36_233',
        'standard_uat_h_36_75',
        'standard_uat_h_36_154',
    ]; 
}
