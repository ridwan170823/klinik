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
       
        if (! Schema::hasColumn('antrians', 'tanggal')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->date('tanggal')->nullable()->after('jadwal_id')->index();
            });
        }

        if (! Schema::hasColumn('antrians', 'status')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('tanggal')->index();
            });
        }

       
        if (Schema::hasColumn('antrians', 'status')) {
            $afterColumn = 'status';
        } elseif (Schema::hasColumn('antrians', 'tanggal')) {
            $afterColumn = 'tanggal';
        } elseif (Schema::hasColumn('antrians', 'jadwal_id')) {
            $afterColumn = 'jadwal_id';
        }

        if (! Schema::hasColumn('antrians', 'nomor')) {

            Schema::table('antrians', function (Blueprint $table) use ($afterColumn) {
                $column = $table->unsignedInteger('nomor')->nullable();

                if ($afterColumn !== null) {
                    $column->after($afterColumn);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       $columnsToDrop = [];

        foreach (['nomor', 'status', 'tanggal'] as $column) {
            if (Schema::hasColumn('antrians', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if ($columnsToDrop !== []) {
            Schema::table('antrians', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};