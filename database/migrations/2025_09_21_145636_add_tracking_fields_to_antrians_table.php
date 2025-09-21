<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

        if ($statusColumnExists) {
                $connection = Schema::getConnection();

                if ($connection->getDriverName() === 'mysql') {
                    $statusColumnDefinition = DB::table('information_schema.columns')
                        ->select('data_type')
                        ->where('table_schema', $connection->getDatabaseName())
                        ->where('table_name', $connection->getTablePrefix() . 'antrians')
                        ->where('column_name', 'status')
                        ->first();

                    $statusColumnIsEnum = $statusColumnDefinition !== null
                        && isset($statusColumnDefinition->data_type)
                        && strtolower($statusColumnDefinition->data_type) === 'enum';
                }
            }

            if ($statusColumnExists && ! $statusColumnIsEnum) {
            $afterColumn = null;
            $statusColumnExists = Schema::hasColumn('antrians', 'status');
            $statusColumnIsEnum = false;

            if (Schema::hasColumn('antrians', 'status')) {
                $afterColumn = 'status';
            } elseif (Schema::hasColumn('antrians', 'tanggal')) {
                $afterColumn = 'tanggal';
            } elseif (Schema::hasColumn('antrians', 'jadwal_id')) {
                $afterColumn = 'jadwal_id';
            }

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