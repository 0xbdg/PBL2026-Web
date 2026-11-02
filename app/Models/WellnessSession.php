<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WellnessSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_wisatawan',
        'lokasi_resort',
        'tanggal_sesi',
        'status_sesi',
    ];

    protected $casts = [
        'tanggal_sesi' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sensorLogs(): HasMany
    {
        return $this->hasMany(SensorLog::class);
    }

    public function latestLog(): HasOne
    {
        return $this->hasOne(SensorLog::class)->latestOfMany('recorded_at');
    }
}