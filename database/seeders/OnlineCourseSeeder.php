<?php

namespace Database\Seeders;

use App\Models\OnlineCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnlineCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (OnlineCourse::count() > 0) {
            return;
        }
        $onlineCourses = [
            ['name' => 'کافی شاپ و باریستا', 'description' => NULL, 'amount' => '3000000', 'discount_amount' => '0', 'discount_start_at' => NULL, 'discount_expire_at' => NULL, 'duration_hour' => '12', 'is_active' => '1'],
            ['name' => 'پخت کیک خامه ای', 'description' => NULL, 'amount' => '1000000', 'discount_amount' => '0', 'discount_start_at' => NULL, 'discount_expire_at' => NULL, 'duration_hour' => '5', 'is_active' => '1'],
            ['name' => 'کیک فوندانت', 'description' => NULL, 'amount' => '1250000', 'discount_amount' => '0', 'discount_start_at' => NULL, 'discount_expire_at' => NULL, 'duration_hour' => '7', 'is_active' => '1'],
            ['name' => 'پخت کیک کافی شاپی', 'description' => NULL, 'amount' => '3000000', 'discount_amount' => '0', 'discount_start_at' => NULL, 'discount_expire_at' => NULL, 'duration_hour' => '9', 'is_active' => '1'],
            ['name' => 'دوره شیرینی پزی (تر و خشک)', 'description' => NULL, 'amount' => '2275000', 'discount_amount' => '0', 'discount_start_at' => NULL, 'discount_expire_at' => NULL, 'duration_hour' => '9', 'is_active' => '1']
        ];
        OnlineCourse::insert($onlineCourses);
    }
}
