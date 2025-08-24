<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        Schema::table('dokters', function (Blueprint $table) {
            if (Schema::hasColumn('dokters', 'jadwal_id')) {
                // lepas FK kalau ada
                try { $table->dropForeign(['jadwal_id']); } catch (\Throwable $e) {}
                $table->dropColumn('jadwal_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dokters', function (Blueprint $table) {
            if (!Schema::hasColumn('dokters', 'jadwal_id')) {
                $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->cascadeOnDelete();
            }
        });
    }
};
