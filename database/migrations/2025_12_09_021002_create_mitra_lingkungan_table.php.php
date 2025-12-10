<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_lingkungan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mitra');
            $table->text('deskripsi');
            $table->string('fokus_area');
            $table->string('url')->nullable();
            // $table->string('no_telp')->nullable();
            // $table->text('alamat')->nullable();
            // $table->string('logo')->nullable(); // opsional untuk upload gambar/logo
            // $table->boolean('status')->default(true); // aktif/tidak aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra');
    }
};
