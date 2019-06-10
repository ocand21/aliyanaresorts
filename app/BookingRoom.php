<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    protected $table = "booking_rooms";

    protected $guarded = [];

    public function room(){
      return $this->belongsTo(Kamar::class, 'no_room');
    }
}
