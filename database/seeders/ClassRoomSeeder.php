<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ClassRoom::where('name', 'class 1')->exists()) {
            return;
        }

        // ClassRoom::insert([
            // [
            //     'name' => 'class 1',
            //     'created_by' => 1,
            //     'capacity' => 12,
            //     'is_active' => true,
            //     'branch_id' => 1,
            //     'number' => '101',
            // ]
        // ]);
    }
}
