<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_temp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('no_room');
            $table->integer('id_users');
            $table->integer('jml_tamu');
            $table->decimal('harga', 10,0);
            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
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
        Schema::dropIfExists('booking_temps');
    }
}
