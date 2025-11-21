<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Seed the districts lookup table.
     */
    public function run(): void
    {
        $districts = [
            ['name' => 'เมืองยะลา', 'lat' => 6.541147, 'lng' => 101.280393],
            ['name' => 'เบตง', 'lat' => 5.774342, 'lng' => 101.072335],
            ['name' => 'บันนังสตา', 'lat' => 6.272222, 'lng' => 101.264444],
            ['name' => 'กรงปินัง', 'lat' => 6.425000, 'lng' => 101.275000],
            ['name' => 'รามัน', 'lat' => 6.478333, 'lng' => 101.423333],
            ['name' => 'ยะหา', 'lat' => 6.445000, 'lng' => 101.145000],
            ['name' => 'ธารโต', 'lat' => 6.145000, 'lng' => 101.185000],
            ['name' => 'กาบัง', 'lat' => 6.420000, 'lng' => 101.015000],
        ];

        foreach ($districts as $data) {
            District::updateOrCreate(
                ['name' => $data['name']],
                ['latitude' => $data['lat'], 'longitude' => $data['lng']]
            );
        }
    }
}
