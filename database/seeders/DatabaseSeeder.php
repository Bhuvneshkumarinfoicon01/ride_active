<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Driver1',
            'email' => 'driver1@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);

        User::factory()->create([
            'name' => 'Driver2',
            'email' => 'driver2@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);

        $this->call(RideSeeder::class);
    }
}
