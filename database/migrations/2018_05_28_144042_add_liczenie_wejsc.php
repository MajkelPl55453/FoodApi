<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLiczenieWejsc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('przepisy', function (Blueprint $table) {
            $table->integer('liczba_wejsc')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('przepisy', function (Blueprint $table) {
            $table->dropColumn('liczba_wejsc');
        });
    }
}
