<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrzepisyKrokisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('przepisy_kroki', function (Blueprint $table) {
            $table->integer('id');
            $table->text('opis');
            $table->text('zdjecie');
            $table->integer('id_przepisu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('przepisy_kroki');
    }
}
