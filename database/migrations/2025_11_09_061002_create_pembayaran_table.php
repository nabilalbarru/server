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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->float('jumlah');
            $table->enum('status',['ditolak','pending','disetujui']);
            $table->string('bukti_transfer', 255);
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_pembayaran');
            $table->foreignId('id_offset')->constrained('offset')->cascadeOnDelete()->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
