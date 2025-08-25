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
        if (!Schema::hasColumn('antrians', 'layanan_id')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->foreignId('layanan_id')->nullable()->constrained('layanans')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('antrians', 'layanan_id')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->dropConstrainedForeignId('layanan_id');
            });
        }
    }
};
