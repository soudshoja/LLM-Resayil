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
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('api_key_id')->nullable();
            $table->string('model');
            $table->integer('tokens_used');
            $table->integer('credits_deducted');
            $table->enum('provider', ['local', 'cloud']);
            $table->integer('response_time_ms');
            $table->integer('status_code');
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('api_key_id')->references('id')->on('api_keys')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
