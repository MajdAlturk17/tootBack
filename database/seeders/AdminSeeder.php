<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Infrastructure\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     */
    public function run(): void
    {
        /** @var User $user */
        $user = User::query()->updateOrCreate([
            'first_name' => 'Admin',
            'email' => 'admin@toot.com',
            'password'=> Hash::make('password')
        ]);

        $role = Role::query()->where('name', Roles::Admin)
            ->where('guard_name', 'api')
            ->first();
        if ($role) {
            $user->assignRole($role);
        } else {
            throw new \Exception('Role not found for the api guard');
        }

    }
}
