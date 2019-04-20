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
            $table->string('type')->default(0);
            $table->string('abnormal')->default(0);
            $table->unsignedInteger('open')->default(0);
            $table->unsignedInteger('turn_off')->default(0);
            $table->unsignedInteger('machine_completion')->default(0);
            $table->unsignedInteger('machine_inputs')->default(0);
            $table->unsignedInteger('machine_completion_day')->default(0);
            $table->unsignedInteger('machine_inputs_day')->default(0);
            $table->unsignedInteger('sensro_inputs')->default(0);
            $table->time('break')->nullable();
            $table->time('break_time')->nullable();
            $table->string('message_state')->nullable();
            $table->time('down_time')->nullable();
            $table->string('completion_status')->nullable();
            $table->unsignedInteger('total_processing_time')->nullable();
            $table->unsignedInteger('second_completion')->default(0);
            $table->string('manufacturing_status')->nullable();
            $table->time('processing_start_time')->nullable();
            $table->time('processing_completion_time')->nullable();
            $table->time('working_time')->nullable();
            $table->double('roll_t')->nullable();
            $table->double('second_t')->nullable();
            $table->time('ct_processing_time')->nullable();
            $table->unsignedInteger('restart_count')->default(0);
            $table->unsignedInteger('restop_count')->default(0);
            $table->unsignedInteger('refueling_start')->default(0);
            $table->unsignedInteger('refueling_end')->default(0);
            $table->time('refueling_time')->nullable();
            $table->time('refueler_time')->nullable();
            $table->unsignedInteger('aggregate_start')->default(0);
            $table->unsignedInteger('aggregate_end')->default(0);
            $table->time('aggregate_time')->nullable();
            $table->time('collector_time')->nullable();
            $table->double('uat-h-36-233')->nullable();
            $table->double('uat-h-36-75')->nullable();
            $table->double('uat-h-36-154')->nullable();
            $table->double('standard_uat-h-36-233')->nullable();
            $table->double('standard_uat-h-36-75')->nullable();
            $table->double('standard_uat-h-36-154')->nullable();
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
