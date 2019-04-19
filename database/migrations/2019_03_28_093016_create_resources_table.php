<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machine_name')->default('roller');
            $table->unsignedInteger('machine_id');         
            $table->string('orderno')->nullable();
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('code');
            $table->date('date');
            $table->time('time');
            $table->unsignedInteger('flag')->default(0);
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
        Schema::dropIfExists('resources');
    }
}
