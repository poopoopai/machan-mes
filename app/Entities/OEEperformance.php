<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class OEEperformance extends Model
{
    protected $fillable = 
    [
        'date',
        'day',
        'weekend', 
        'work_name',
        'standard_working_hours',
        'total_hours',
        'machine_processing',
        'actual_production_quantity',
        'standard_completion',
        'total_input_that_day',
        'total_completion_that_day',
        'adverse_number',
        'mass_production_time',
        'total_downtime',
        'standard_processing_seconds',
        'actual_processing_seconds',
        'updown_time',
        'correction_time',
        'hanging_time',
        'aggregate_time',
        'break_time',
        'chang_model_and_line',
        'machine_downtime',
        'bad_disposal_time',
        'model_damge_change_line_time',
        'program_modify_time',
        'machine_maintain_time',
        'excluded_working_hours',
        'machine_utilization_rate',
        'performance_rate',
        'yield',
        'OEE',
        
    ];
}
