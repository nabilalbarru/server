<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->id();
            $table->dateTime('mulai_perjalanan');
            $table->dateTime('selesai_perjalanan');
            $table->decimal('emisi_perjalanan', 8,2);
            $table->foreignId('id_pengendara')->constrained('pengendara_umum')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_kendaraan')->constrained('kendaraan')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking');
    }
};
