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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique()->comment('Unique template code (welcome, subscription_confirmed, etc.)');
            $table->string('name')->comment('Verbose template name');
            $table->text('arabic_content')->comment('Arabic version of the template');
            $table->text('english_content')->comment('English version of the template');
            $table->enum('default_language', ['ar', 'en'])->default('ar');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
