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
            'เมืองยะลา',
            'เบตง',
            'บันนังสตา',
            'กรงปินัง',
            'รามัน',
            'ยะหา',
            'ธารโต',
            'กาบัง',
            'กะพ้อ',
        ];

        foreach ($districts as $name) {
            District::firstOrCreate(['name' => $name]);
        }
    }
}
