<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipeKamar extends Model
{
    protected $table = "tipe_kamar";

    protected $guarded = [];

    public function kamar(){
      return $this->hasMany(Kamar::class, 'id_tipe', 'id');
    }
}
