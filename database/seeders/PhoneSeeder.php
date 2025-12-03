<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Phone;
use App\Models\PhoneInternal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Phone::exists()) {
            return;
        }

        foreach (Branch::all() as $key => $branch) {
            Phone::create([
                'number' => '0218888100' . ($key + 1),
                'branch_id' => $branch->id,
            ]);
        }

        foreach (Phone::all() as $key => $phone) {
            PhoneInternal::create([
                'title' => fake()->name(),
                'number' => time(),
                'phone_id' => 1,
                'secretary_id' => 1,
                'is_active' => true,
                'created_by' => 1,
            ]);
        }
    }
}
