<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable = [
        'device_name',
        'esp32_topic',
        'gsr_topic',
        'heartrate_topic',
        'temp_topic'
    ];

    public function sessions()
    {
        return $this->hasMany(WellnessSession::class);
    }
}
