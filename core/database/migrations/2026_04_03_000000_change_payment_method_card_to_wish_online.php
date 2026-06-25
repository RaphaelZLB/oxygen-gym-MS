<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('payments')->where('method', 'card')->update(['method' => 'wish-money']);

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $column = DB::selectOne(
            'SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            ['payments', 'method'],
        );

        if ($column && str_contains((string) $column->COLUMN_TYPE, 'card')) {
            DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'wish-money') NOT NULL");
        }
    }

    public function down(): void
    {
        DB::table('payments')->where('method', 'wish-money')->update(['method' => 'card']);

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $column = DB::selectOne(
            'SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            ['payments', 'method'],
        );

        if ($column && str_contains((string) $column->COLUMN_TYPE, 'wish-money')) {
            DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'card') NOT NULL");
        }
    }
};
