<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_booking');
            $table->string('id_pelanggan');
            $table->string('nama_pemilik_rekening');
            $table->string('no_rekening');
            $table->integer('id_metode');
            $table->date('tgl_transfer');
            $table->decimal('jumlah', 10,0);
            $table->integer('id_user')->nullable();
            $table->string('status')->default('0');
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
        Schema::dropIfExists('pembayarans');
    }
}
