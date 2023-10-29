<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ronda extends Model
{
    use HasFactory;
    protected $table = "ronda";
    public $timestamps = false;
    protected $hidden = ['pass'];
}
