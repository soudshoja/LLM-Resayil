<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user created by UserSeeder
        $adminUser = User::where('email', 'admin@llm.resayil.io')->first();

        if (!$adminUser) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $plans = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $adminUser->id,
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
