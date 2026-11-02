<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorLog extends Model
{
    use HasFactory;

    public const STATUS = ['Rileks', 'Stres Akut', 'Pemulihan'];

    // Tabel sensor_logs menggunakan recorded_at sebagai penanda waktu.
    public $timestamps = false;

    protected $fillable = [
        'wellness_session_id',
        'detak_jantung',
        'suhu_kulit',
        'gsr_eda',
        'tingkat_relaksasi',
        'status_klasifikasi',
        'recorded_at',
    ];

    protected $casts = [
        'detak_jantung' => 'integer',
        'suhu_kulit' => 'float',
        'gsr_eda' => 'float',
        'tingkat_relaksasi' => 'float',
        'recorded_at' => 'datetime',
    ];

    public function wellnessSession(): BelongsTo
    {
        return $this->belongsTo(WellnessSession::class);
    }

    /**
     * Kelas Tailwind untuk badge status klasifikasi: $log->badge_class
     */
    protected function badgeClass(): Attribute
    {
        return Attribute::get(fn () => match ($this->status_klasifikasi) {
            'Rileks' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'Stres Akut' => 'bg-rose-100 text-rose-700 ring-rose-200',
            'Pemulihan' => 'bg-amber-100 text-amber-700 ring-amber-200',
            default => 'bg-slate-100 text-slate-600 ring-slate-200',
        });
    }
}
