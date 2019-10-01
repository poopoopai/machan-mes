<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayPerformanceStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_performance_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->date('report_work_date');
            $table->string('work_name');
            $table->unsignedInteger('standard_working_hours')->nullable();
            $table->time('total_hours')->nullable();
            $table->string('machine_code');
            $table->string('machine_name');
            $table->string('production_category');
            $table->string('order_number')->nullable();  //製令單號
            $table->string('material_name');
            $table->unsignedInteger('production_quantity')->nullable();
            $table->unsignedInteger('machine_processing');
            $table->unsignedInteger('actual_production_quantity');
            $table->unsignedInteger('standard_completion');
            $table->unsignedInteger('total_input_that_day');
            $table->unsignedInteger('total_completion_that_day');
            $table->unsignedInteger('adverse_number');
            $table->float('standard_processing')->nullable();
            $table->float('standard_updown')->nullable();
            $table->time('mass_production_time');
            $table->time('total_downtime');
            $table->integer('standard_processing_seconds')->nullable();
            $table->double('actual_processing_seconds')->nullable();
            $table->string('machine_speed')->nullable();
            $table->float('updown_time')->nullable();
            $table->time('correction_time')->nullable();
            $table->time('hanging_time');
            $table->time('aggregate_time');
            $table->time('break_time');
            $table->string('chang_model_and_line')->nullable();
            $table->time('bad_disposal_time')->nullable();
            $table->time('model_damge_change_line_time')->nullable();
            $table->time('program_modify_time')->nullable();
            $table->time('meeting_time')->nullable();
            $table->time('environmental_arrange_time')->nullable();
            $table->time('excluded_working_hours');
            $table->time('machine_downtime');
            $table->time('machine_maintain_time')->nullable();
            $table->float('machine_utilization_rate');
            $table->float('performance_rate');
            $table->float('yield');
            $table->float('OEE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_performance_statistics');
    }
}
