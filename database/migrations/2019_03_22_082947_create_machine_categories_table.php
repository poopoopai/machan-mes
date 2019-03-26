<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_categories', function (Blueprint $table) {
            $table->increments('no');
            $table->string('machine_id');
            $table->string('machine_name');
            $table->string('tpye');
            $table->string('auto');
            $table->boolean('auto_up')->default(false);
            $table->boolean('auto_down')->default(false);
            $table->boolean('arrange')->default(false);
            $table->boolean('auto_arrange')->default(false);
            $table->boolean('auto_change')->default(false);
            $table->boolean('auto_pay')->default(false);
            $table->boolean('auto_finish')->default(false);
            $table->string('interface');
            $table->string('data_integration');
            $table->string('break_time');
            $table->string('machine_type');
            $table->string('remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_categories');
    }
}
