<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DistrictSeeder::class,
            AffiliationSeeder::class,
            DisasterReportSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@yala-flood.peo'],
            [
                'name' => 'System Administrator',
                'role' => 'admin',
                'password' => Hash::make('Yala@pe021'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'data-entry@yala-flood.peo'],
            [
                'name' => 'Data Entry User',
                'role' => 'data-entry',
                'password' => Hash::make('ChangeMe123!'),
            ]
        );

        User::factory()->count(3)->create();
    }
}
