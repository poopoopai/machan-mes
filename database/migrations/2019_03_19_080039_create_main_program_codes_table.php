<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainProgramCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_program_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('status');
            $table->string('description');
            $table->string('type')->nullable();
            $table->string('codeX')->nullable();
            $table->string('group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_program_codes');
    }
}
