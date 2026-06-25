<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->text('medical_notes')->nullable()->after('status');
            $table->json('tags')->nullable()->after('medical_notes');
            $table->string('training_time', 10)->nullable()->after('tags');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE members MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'active'");
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('is_renewal')->default(false)->after('status');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('is_renewal');
            $table->decimal('final_price', 10, 2)->nullable()->after('discount_amount');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->string('plan_kind', 20)->default('individual')->after('price');
        });

        $subscriptions = DB::table('subscriptions')->get();
        foreach ($subscriptions as $sub) {
            $price = DB::table('plans')->where('id', $sub->plan_id)->value('price');
            if ($price !== null) {
                DB::table('subscriptions')->where('id', $sub->id)->update(['final_price' => $price]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('plan_kind');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['is_renewal', 'discount_amount', 'final_price']);
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['medical_notes', 'tags', 'training_time']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE members MODIFY COLUMN status ENUM('active','inactive') NOT NULL DEFAULT 'active'");
        }
    }
};
