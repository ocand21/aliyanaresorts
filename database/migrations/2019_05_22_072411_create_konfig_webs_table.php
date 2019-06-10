<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKonfigWebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konfig_web', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hotel_name');
            $table->string('web');
            $table->string('alamat');
            $table->string('mail1');
            $table->string('mail2');
            $table->text('instagram');
            $table->text('facebook');
            $table->string('fax');
            $table->string('phone');
            $table->string('whatsapp');
            $table->text('visi');
            $table->text('misi');
            $table->text('tentang');
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
        Schema::dropIfExists('konfig_webs');
    }
}
