<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Events\RideAssigned;

class RideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = \App\Models\User::where('role', 'driver')->get();
        if ($drivers->isEmpty()) {
            $this->command->info('No drivers found. Please seed users first.');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $driver = $drivers->random();
            $ride = \App\Models\Ride::create([
                'driver_id' => $driver->id,
                'pickup_location' => 'Pickup Location ' . $i,
                'drop_location' => 'Drop Location ' . $i,
                'status' => 'assigned',
            ]);
            event(new RideAssigned($ride));
        }
        $this->command->info('Finished seeding rides.');
    }
}
