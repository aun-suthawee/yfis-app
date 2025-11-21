<?php

namespace Database\Seeders;

use App\Models\DisasterReport;
use Illuminate\Database\Seeder;

class DisasterReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DisasterReport::factory(50)->create();
    }
}
