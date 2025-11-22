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
        // $this->call([
        //     DistrictSeeder::class,
        //     AffiliationSeeder::class,
        //     DisasterReportSeeder::class,
        // ]);

        User::updateOrCreate(
            ['email' => 'admin@yala-flood.peo'],
            [
                'name' => 'System Administrator',
                'role' => 'admin',
                'password' => Hash::make('Yala@pe021'),
            ]
        );

        User::factory()->count(1)->create();
    }
}
