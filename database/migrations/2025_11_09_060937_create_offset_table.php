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
        Schema::create('offset', function (Blueprint $table) {
            $table->id();
            $table->decimal('jumlah_emisi', 8,2);
            $table->timestamp('tanggal_offset');
            $table->foreignId('id_pengguna')->constrained('pengendara_umum')->cascadeOnDelete()->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offset');
    }
};
