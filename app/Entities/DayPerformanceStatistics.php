<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DayPerformanceStatistics extends Model
{
    protected $fillable = 
    [   
        'report_work_date',
        'work_name',
        'standard_working_hours', 
        'total_hours',
        'machine_code',
        'machine_name',
        'production_category',
        'order_number',
        'material_name',
        'production_quantity',
        'machine_processing',
        'actual_production_quantity',
        'standard_completion',
        'total_input_that_day',
        'total_completion_that_day',
        'adverse_number',
        'standard_processing',
        'standard_updown',
        'mass_production_time',
        'total_downtime',
        'standard_processing_seconds',
        'actual_processing_seconds',
        'machine_speed',
        'updown_time',
        'correction_time',
        'hanging_time',
        'aggregate_time',
        'break_time',
        'chang_model_and_line',
        'bad_disposal_time',
        'model_damge_change_line_time',
        'program_modify_time',
        'meeting_time',
        'environmental_arrange_time',
        'excluded_working_hours',
        'machine_downtime',
        'machine_maintain_time',
        'machine_utilization_rate',
        'performance_rate',
        'yield',
        'OEE'
    ];
}
