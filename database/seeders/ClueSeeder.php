<?php

namespace Database\Seeders;

use App\Models\Clue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Clue::count() > 0) {
            return;
        }

        // Clue::create([
        //     'user_id' => 1,
        //     'branch_id' => 1,
        //     'secretary_id' => 1,
        //     'familiarity_way_id' => 1,
        //     'created_by' => 1,
        // ]);
    }
}
