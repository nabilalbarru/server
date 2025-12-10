<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracking', function (Blueprint $table) {
            // Stores JSON string of coordinates: "[{"lat":-6.1, "lng":106.1}, {"lat":-6.2, "lng":106.2}]"
            $table->longText('route_history')->nullable()->after('jarak_tempuh');
        });
    }

    public function down(): void
    {
        Schema::table('tracking', function (Blueprint $table) {
            $table->dropColumn('route_history');
        });
    }
};
