<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Profession::where('title', 'Profession 1')->exists()) {
            return;
        }

        // Profession::create([
        //     'title' => 'Profession 1',
        //     'public_price' => 1000000,
        //     'private_price' => 2000000,
        //     'public_capacity' => 10,
        //     'private_capacity' => 20,
        //     'public_duration_hours' => 10,
        //     'private_duration_hours' => 20,
        //     'created_by' => 1,
        // ]);
    }
}
