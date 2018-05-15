<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrzepisiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('przepisy', function (Blueprint $table) {
            $table->integer('id');
            $table->string('nazwa', 255);
            $table->integer('kategoria');
            $table->text('zdjecie');
            $table->string('czas_przygotowania', 255);
            $table->string('trudnosc', 255);
            $table->string('ilosc_porcji', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('przepisy');
    }
}
