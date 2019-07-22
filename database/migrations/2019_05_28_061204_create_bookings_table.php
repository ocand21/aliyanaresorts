<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_booking')->unique();
            $table->integer('id_pelanggan');
            $table->decimal('total',10,0);
            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
            $table->string('keterangan')->nullable();
            $table->integer('status');
            $table->integer('id_users');
            $table->string('tipe');
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
        Schema::dropIfExists('bookings');
    }
}
