<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Course::count() > 0) {
            return;
        }

        // Course::create([
        //     'title' => 'Sample Course',
        //     'capacity' => 20,
        //     'start_date' => now(),
        //     'end_date' => now()->addDays(30),
        //     'start_time' => '09:00:00',
        //     'end_time' => '12:00:00',
        //     'price' => 1000000,
        //     'week_days' => ['saturday', 'sunday', 'monday'],
        //     'duration_hours' => 3,
        //     'profession_id' => 1,
        //     'teacher_id' => 1,
        //     'branch_id' => 1,
        //     'class_room_id' => 1,
        //     'course_type' => 'public',
        //     'created_by' => 2
        // ]);
    }
}
