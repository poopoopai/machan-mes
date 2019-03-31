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
            $table->increments('id');
            $table->string('machine_id')->nullable();
            $table->string('machine_name');
            $table->string('type');
            $table->string('auto');
            $table->boolean('auto_up')->default(false);
            $table->boolean('auto_down')->default(false);
            $table->boolean('arrange')->default(false);
            $table->boolean('auto_arrange')->default(false);
            $table->boolean('auto_change')->default(false);
            $table->boolean('auto_pay')->default(false);
            $table->boolean('auto_finish')->default(false);
            $table->string('interface');
            $table->string('data_integration')->nullable();
            $table->string('break_time');
            $table->string('machine_type')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('machine_categories');
    }
}
