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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->enum('tier', ['basic', 'pro', 'enterprise']);
            $table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
            $table->string('MyFatoorah_invoice_id')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->integer('credits_purchased')->default(0);
            $table->integer('credits_used')->default(0);
            $table->boolean('auto_renew')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
