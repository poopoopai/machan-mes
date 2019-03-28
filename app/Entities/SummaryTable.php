<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SummaryTable extends Model
{
    protected $fillable = 
    [
        'description',
        'unit',
        'abnormal_state',
        'open',
        'turn_off',
        'machine_completion',
        'machine_inputs',
        'machine_completion_day',
        'machine_inputs_day',
        'sensro_inputs',
        'break',
        'break_time',
        'message_state',
        'down_time',
        'completion_status',
        'total_processing_time',
        'second_completion',
        'manufacturing_status',
        'processing_start_time',
        'processing_completion_time',
        'working_time',
        'roll_T',
        'second_T',
        'CT_processing_time',
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
        'UAT-H-36-233',
        'UAT-H-36-75',
        'UAT-H-36-154',
        'standard_UAT-H-36-233',
        'standard_UAT-H-36-75',
        'standard_UAT-H-36-154',
    ]; 
}
