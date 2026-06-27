<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE subscriptions MODIFY plan_id CHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
        } else {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->string('plan_id', 36)->nullable()->change();
            });
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('plan_id')->references('id')->on('plans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        DB::table('subscriptions')->whereNull('plan_id')->delete();

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE subscriptions MODIFY plan_id CHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
        } else {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->string('plan_id', 36)->nullable(false)->change();
            });
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('plan_id')->references('id')->on('plans');
        });
    }
};
