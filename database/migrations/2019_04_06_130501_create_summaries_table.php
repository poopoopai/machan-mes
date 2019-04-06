<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('unit');
            $table->string('abnormal_state');
            $table->unsignedInteger('open');
            $table->unsignedInteger('turn_off');
            $table->unsignedInteger('machine_completion');
            $table->unsignedInteger('machine_inputs');
            $table->unsignedInteger('machine_completion_day');
            $table->unsignedInteger('machine_inputs_day');
            $table->unsignedInteger('sensro_inputs');
            $table->time('break');
            $table->time('break_time');
            $table->string('message_state');
            $table->time('down_time');
            $table->string('completion_status');
            $table->unsignedInteger('total_processing_time');
            $table->unsignedInteger('second_completion');
            $table->string('manufacturing_status');
            $table->time('processing_start_time');
            $table->time('processing_completion_time');
            $table->time('working_time');
            $table->double('roll_t');
            $table->double('second_t');
            $table->time('ct_processing_time');
            $table->unsignedInteger('restart_count');
            $table->unsignedInteger('restop_count');
            $table->unsignedInteger('refueling_start');
            $table->unsignedInteger('refueling_end');
            $table->time('refueling_time');
            $table->time('refueler_time');
            $table->unsignedInteger('aggregate_start');
            $table->unsignedInteger('aggregate_end');
            $table->time('aggregate_time');
            $table->time('collector_time');
            $table->double('uat-h-36-233');
            $table->double('uat-h-36-75');
            $table->double('uat-h-36-154');
            $table->double('standard_uat-h-36-233');
            $table->double('standard_uat-h-36-75');
            $table->double('standard_uat-h-36-154');
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
        Schema::dropIfExists('summaries');
    }
}
