<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create 5 Admins
        for ($i = 1; $i <= 5; $i++) {
            $admin = User::create([
                'name' => $faker->name,
                'email' => "admin{$i}@example.com",
                'password' => Hash::make('password'),
            ]);
            $admin->assignRole($adminRole);
        }

        // Create 5 Users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
            ]);
            $user->assignRole($userRole);
        }
    }
}
