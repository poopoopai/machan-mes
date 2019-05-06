<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardCtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_cts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orderno')->nullable();
            $table->float('standard_ct')->nullable();
            $table->float('standard_updown')->nullable();
            $table->float('standard_processing')->nullable();
            $table->string('machine');
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
        Schema::dropIfExists('standard_cts');
    }
}
