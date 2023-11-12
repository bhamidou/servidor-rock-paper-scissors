<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    use HasFactory;
    protected $table = "partida";
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_ronda',
        'tirada_user_1',
        'tirada_user_2',
        'ganador'
      ];

}
