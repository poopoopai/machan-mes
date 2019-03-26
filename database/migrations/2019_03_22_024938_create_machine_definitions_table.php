<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machine_id');
            $table->string('machine_name');
            $table->string('machine_category');
            $table->string('machine_category_name');
            $table->string('aps_process_code');//字串相加
            $table->string('process_description');
            $table->string('api_integration')->nullable();
            $table->string('api_integration_name')->nullable();
            $table->string('group_setting');
            $table->string('oee_assign')->nullable();
            $table->string('device_id')->nullable();
            $table->string('machine_specification')->nullable();
            $table->unsignedInteger('class_assign');
            $table->unsignedInteger('production_time');
            $table->unsignedInteger('change_line_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_definitions');
    }
}
