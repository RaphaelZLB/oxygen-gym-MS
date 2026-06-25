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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('member_id')->index();
            $table->uuid('subscription_id')->index();

            $table->decimal('amount', 10, 2);
            $table->enum('method', ['cash', 'wish-money'])->index();
            $table->dateTime('paid_at')->index();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
