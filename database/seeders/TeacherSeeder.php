<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Teacher::where('user_id', User::where('mobile', '09191111111')->first()?->id)->exists()) {
            return;
        }

        $user = User::create([
            'first_name' => 'Teacher',
            'last_name' => 'Teacher',
            'email' => 'teacher@teacher.com',
            'mobile' => '09191111111',
            'is_admin' => false,
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'is_active' => true,
            'start_date' => now(),
            'leaving_date' => null,
            'created_by' => 1,
        ]);
    }
}
