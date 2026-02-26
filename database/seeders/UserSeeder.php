<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@llm.resayil.io',
            'phone' => '96500000000',
            'password' => Hash::make('password'),
            'plan' => 'enterprise',
            'credits_remaining' => 100000,
            'credits_total' => 100000,
            'is_active' => true,
        ]);
    }
}
