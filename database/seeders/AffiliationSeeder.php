<?php

namespace Database\Seeders;

use App\Models\Affiliation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AffiliationSeeder extends Seeder
{
    /**
     * Seed the affiliations lookup table.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Affiliation::truncate();
        Schema::enableForeignKeyConstraints();

        $affiliations = [
            'สำนักงานคณะกรรมการการศึกษาขั้นพื้นฐาน',
            'สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน',
            'กรมส่งเสริมการเรียนรู้',
            'สำนักงานคณะกรรมการการอาชีวศึกษา',
            'สำนักงานปลัดกระทรวงการอุดมศึกษา วิทยาศาสตร์ วิจัยและนวัตกรรม',
            'กรมส่งเสริมการปกครองท้องถิ่น',
            'มหาวิทยาลัยการกีฬาแห่งชาติ',
            'สำนักงานพระพุทธศาสนาแห่งชาติ',
            'กองบัญชาการตำรวจตระเวนชายแดน',
        ];

        foreach ($affiliations as $name) {
            Affiliation::create(['name' => $name]);
        }
    }
}
