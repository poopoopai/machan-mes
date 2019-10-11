<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('so_id');
            $table->string('item');
            $table->string('customer_name');
            $table->unsignedInteger('qty');
            $table->date('container_date')->nullable();
            $table->date('bill_date');
            $table->string('org_id');
            $table->string('current_state');
            $table->string('status');
            $table->string('customer_order');
            $table->string('person_id');
            $table->string('material_spec')->nullable();
            $table->string('sunit_id');
            $table->unsignedInteger('untrans_qty');
            $table->string('cu_remark', 2048);
            $table->unsignedInteger('batch');
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
        Schema::dropIfExists('sale_orders');
    }
}
