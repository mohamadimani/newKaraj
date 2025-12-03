<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Secretary;

class SecretarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Secretary::count() > 0) {
            return;
        }

        Secretary::create([
            'user_id' => 1,
            'created_by' => 1,
            'start_date' => now(),
            'leaving_date' => now(),
        ]);
    }
}
