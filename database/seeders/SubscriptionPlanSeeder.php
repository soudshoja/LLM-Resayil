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
                'name' => 'Basic',
                'price_kwd' => 25.000,
                'credits' => 15000,
                'api_keys' => 1,
                'queue_priority' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'price_kwd' => 45.000,
                'credits' => 50000,
                'api_keys' => 2,
                'queue_priority' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'price_kwd' => 75.000,
                'credits' => 50000,
                'api_keys' => 3,
                'queue_priority' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subscriptions')->insert($plans);
    }
}
