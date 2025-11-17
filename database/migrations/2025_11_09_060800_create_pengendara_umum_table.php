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
        Schema::create('pengendara_umum', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengguna',150);
            $table->string('email', 150)->unique();
            $table->string('kata_sandi', 100);
            $table->timestamp('tanggal_registrasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengendara_umum');
    }
};
