<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySummariesColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->string('refueling_start')->default(0)->change();
            $table->string('refueling_end')->default(0)->change();
            $table->string('aggregate_start')->default(0)->change();
            $table->string('aggregate_end')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropColumn('refueling_start');
            $table->dropColumn('refueling_end');
        });
    }
}
