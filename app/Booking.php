<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = "bookings";

    protected $guarded = [];


    public function room(){
      return $this->belongsTo(Kamar::class, 'id');
    }
}
