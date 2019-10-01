<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessRoutingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_routings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('process_routing_id');
            $table->string('process_routing_name');
            $table->string('factory')->default('1000');
            $table->string('factory_id');
            $table->string('internal_code')->default('001');
            $table->string('status')->default('2');
            $table->string('org_id');
            $table->string('transfer_factory');
            $table->string('factory_type');
            $table->string('routing_level');
            $table->string('aps_id');
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
        Schema::dropIfExists('process_routings');
    }
}
