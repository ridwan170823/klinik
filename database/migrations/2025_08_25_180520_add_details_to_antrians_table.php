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
        Schema::table('antrians', function (Blueprint $table) {
            $table->foreignId('dokter_id')->nullable()->constrained('dokters')->nullOnDelete()->after('nomor_antrian');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->nullOnDelete()->after('dokter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dokter_id');
            $table->dropConstrainedForeignId('jadwal_id');
        });
    }
};