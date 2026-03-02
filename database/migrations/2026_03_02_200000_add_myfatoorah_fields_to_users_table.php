<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('myfatoorah_payment_profile_id')->nullable()->after('trial_credits_remaining');
            $table->string('myfatoorah_subscription_id')->nullable()->after('myfatoorah_payment_profile_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['myfatoorah_payment_profile_id', 'myfatoorah_subscription_id']);
        });
    }
};
