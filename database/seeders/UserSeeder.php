<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::where('mobile', '09191930406')->exists()) {
            return;
        }

        User::insert([
            [
                'first_name' => 'FIDAR',
                'last_name' => 'AI',
                'email' => 'fidar_ai@newdeniz.com',
                'mobile' => '09121111111',
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'mani',
                'last_name' => 'mohamadi',
                'email' => 'admin@admin.com',
                'mobile' => '09191930406',
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@gmail.com',
                'mobile' => '09121234567',
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
