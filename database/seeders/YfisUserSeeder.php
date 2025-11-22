<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class YfisUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 14 YFIS users (YFIS01 to YFIS14)
        // Each user is assigned to a different affiliation (1-14)
        for ($i = 1; $i <= 14; $i++) {
            $username = sprintf('YFIS%02d', $i);
            
            User::create([
                'name' => $username,
                'username' => $username,
                'email' => null, // YFIS users don't need email
                'password' => Hash::make('123456'),
                'role' => 'yfis',
                'affiliation_id' => $i,
                'address' => null,
                'tel' => null,
            ]);
        }

        $this->command->info('Created 14 YFIS users (YFIS01 to YFIS14) with password: 123456');
    }
}
