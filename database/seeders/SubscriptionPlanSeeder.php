<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'user_id' => \Illuminate\Support\Str::uuid(),
                'tier' => 'basic',
                'status' => 'active',
                'MyFatoorah_invoice_id' => null,
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
                'credits_purchased' => 15000,
                'credits_used' => 0,
                'auto_renew' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subscriptions')->insert($plans);
    }
}
