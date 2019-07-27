<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePelangganWIGSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan_wig', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('nama');
          $table->string('email')->unique()->nullable();
          $table->string('tipe_identitas');
          $table->string('no_identitas')->unique();
          $table->string('no_telepon')->unique();
          $table->string('alamat');
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
        Schema::dropIfExists('pelanggan_w_i_g_s');
    }
}
