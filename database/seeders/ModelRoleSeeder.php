<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ModelRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Role::count() == 0) {
            return;
        }
        $adminRole = Role::where('name', 'admin')->first();
        $adminUser = User::where('mobile', '09121234567')->first();
        $adminUser->assignRole($adminRole);
        $adminUser->givePermissionTo($adminRole->permissions);
        $adminUser2 = User::where('mobile', '09191930406')->first();
        $adminUser2->assignRole($adminRole);
        $adminUser2->givePermissionTo($adminRole->permissions);
    }
}
