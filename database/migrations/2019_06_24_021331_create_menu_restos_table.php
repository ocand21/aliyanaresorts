<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuRestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_resto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu');
            $table->string('nama');
            $table->decimal('harga', 10,0);
            $table->string('catatan')->nullable();
            $table->string('foto')->nullable();
            $table->string('nama_file')->nullable();
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
        Schema::dropIfExists('menu_resto');
    }
}
