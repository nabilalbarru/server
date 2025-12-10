<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracking', function (Blueprint $table) {
            $table->dropForeign('tracking_id_pengendara_foreign');
            
            $table->foreign('id_pengendara')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tracking', function (Blueprint $table) {
            $table->dropForeign(['id_pengendara']);
                
            $table->foreign('id_pengendara')
                  ->references('id')
                  ->on('pengendara_umum')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
};
