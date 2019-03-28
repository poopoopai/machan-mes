<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummaryTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary_tables', function (Blueprint $table) {
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
            $table->double('roll_T');
            $table->double('second_T');
            $table->time('CT_processing_time');
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
            $table->double('UAT-H-36-233');
            $table->double('UAT-H-36-75');
            $table->double('UAT-H-36-154');
            $table->double('standard_UAT-H-36-233');
            $table->double('standard_UAT-H-36-75');
            $table->double('standard_UAT-H-36-154');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summary_tables');
    }
}
