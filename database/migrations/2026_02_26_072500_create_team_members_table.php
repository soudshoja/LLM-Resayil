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
        Schema::create('team_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_owner_id');
            $table->uuid('member_user_id');
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->uuid('added_by_id');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate team members
            $table->unique(['team_owner_id', 'member_user_id']);

            // Foreign keys with cascade delete
            $table->foreign('team_owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('member_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('added_by_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
