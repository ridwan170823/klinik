<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrians', fn (Blueprint $table) =>
            $table->foreignId('layanan_id')->nullable()->constrained('layanans')->nullOnDelete()
        );
    }

    public function down(): void
    {
        Schema::table('antrians', fn (Blueprint $table) =>
            $table->dropConstrainedForeignId('layanan_id')
        );
    }
};