<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = "kamar";

    protected $guarded = [];


    public function booking(){
      return $this->hasOne(BookingRoom::class, 'no_room', 'no_room');
    }

    public function tipe_kamar(){
      return $this->belongsTo(TipeKamar::class, 'id_tipe', 'id');
    }
}
