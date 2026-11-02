<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wellness_session_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('detak_jantung');          // BPM - MAX30102
            $table->decimal('suhu_kulit', 4, 2);                    // °C  - MAX30205
            $table->decimal('gsr_eda', 5, 2);                       // µS  - Grove-GSR
            $table->decimal('tingkat_relaksasi', 5, 2);             // %   - output XGBoost
            $table->enum('status_klasifikasi', ['Rileks', 'Stres Akut', 'Pemulihan']);
            $table->timestamp('recorded_at')->useCurrent();

            $table->index(['wellness_session_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};