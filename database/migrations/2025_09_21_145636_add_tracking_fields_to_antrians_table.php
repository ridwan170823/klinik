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
           if (! Schema::hasColumn('antrians', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('jadwal_id')->index();
            }

            if (! Schema::hasColumn('antrians', 'status')) {
                $table->string('status')->default('pending')->after('tanggal')->index();
            }

            if (! Schema::hasColumn('antrians', 'nomor')) {
                $table->unsignedInteger('nomor')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('antrians', 'tanggal')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->dropColumn('tanggal');
            });
        }

        if (Schema::hasColumn('antrians', 'status')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasColumn('antrians', 'nomor')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->dropColumn('nomor');
            });
        }
    }
};