<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->updateOrCreate(['name' => Roles::Admin,'guard_name' => 'api']);
        Role::query()->updateOrCreate(['name' => Roles::Vendor,'guard_name' => 'api']);
        Role::query()->updateOrCreate(['name' => Roles::Customer,'guard_name' => 'api']);
    }
}
