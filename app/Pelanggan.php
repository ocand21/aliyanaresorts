<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Model
{
    use Notifiable;

    protected $table = "pelanggan";

    protected $guarded = [];

}
