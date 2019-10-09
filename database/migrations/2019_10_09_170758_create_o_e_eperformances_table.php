<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOEEperformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_e_eperformances', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('day');
            $table->string('weekend')->nullable();
            $table->string('work_name')->nullable();
            $table->unsignedInteger('standard_working_hours')->nullable();
            $table->time('total_hours')->nullable();
            $table->unsignedInteger('machine_processing')->nullable();
            $table->unsignedInteger('actual_production_quantity')->nullable();
            $table->unsignedInteger('standard_completion')->nullable();
            $table->unsignedInteger('total_input_that_day')->nullable();
            $table->unsignedInteger('total_completion_that_day')->nullable();
            $table->unsignedInteger('adverse_number')->nullable();
            $table->time('mass_production_time')->nullable();
            $table->time('total_downtime')->nullable();
            $table->integer('standard_processing_seconds')->nullable();
            $table->double('actual_processing_seconds')->nullable();
            $table->float('updown_time')->nullable();
            $table->time('correction_time')->nullable();
            $table->time('hanging_time');
            $table->time('aggregate_time');
            $table->time('break_time');
            $table->string('chang_model_and_line')->nullable();
            $table->time('machine_downtime')->nullable();
            $table->time('bad_disposal_time')->nullable();
            $table->time('model_damge_change_line_time')->nullable();
            $table->time('program_modify_time')->nullable();
            $table->time('machine_maintain_time')->nullable();
            $table->time('excluded_working_hours');
            $table->float('machine_utilization_rate')->nullable();
            $table->float('performance_rate')->nullable();
            $table->float('yield')->nullable();
            $table->float('OEE')->nullable();
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
        Schema::dropIfExists('o_e_eperformances');
    }
}
