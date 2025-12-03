<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Branch::exists()) {
            return;
        }

        Branch::insert([
            [
                'name' => 'دنیز',
                'address' => 'تهران - خیابان کارگر',
                'minimum_pay' => 500000,
            ]
        ]);
    }
}
