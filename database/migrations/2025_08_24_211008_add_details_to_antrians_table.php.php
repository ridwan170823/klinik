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
            $table->unsignedBigInteger('dokter_i')->nullable()->after('nomor_antrian');
            $table->unsignedBigInteger('jadwal_i')->nullable()->after('dokter_i');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropColumn(['dokter_i', 'jadwal_i']);
        });
    }
};