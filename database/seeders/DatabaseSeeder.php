<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BranchSeeder::class,
            ProvinceSeeder::class,
            HowFoundUsSeeder::class,
            PaymentMethodSeeder::class,
            TechnicalAddressSeeder::class,
            RolePermissionSeeder::class,
            OnlineCourseSeeder::class,
            ModelRoleSeeder::class,
            // for development test data
            // SecretarySeeder::class,
            // PhoneSeeder::class,
            // TeacherSeeder::class,
            // ClassRoomSeeder::class,
            // ProfessionSeeder::class,
            // ClueSeeder::class,
            // CourseSeeder::class,
        ]);
    }
}
