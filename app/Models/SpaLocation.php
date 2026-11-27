<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaLocation extends Model
{
    use HasFactory;
    protected $table = "spa_locations";
    protected $fillable = ['spa_name', 'address', 'latitude', 'longitude'];
}
