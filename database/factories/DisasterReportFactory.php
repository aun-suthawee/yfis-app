<?php

namespace Database\Factories;

use App\Models\Affiliation;
use App\Models\DisasterReport;
use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DisasterReport>
 */
class DisasterReportFactory extends Factory
{
    protected $model = DisasterReport::class;

    public function definition(): array
    {
        $reportedAt = $this->faker->dateTimeBetween('-10 days', 'now');
        $damageBuilding = $this->faker->randomFloat(2, 1000, 2000000);
        $damageEquipment = $this->faker->randomFloat(2, 1000, 1500000);
        $damageMaterial = $this->faker->randomFloat(2, 1000, 800000);
        $damageTotal = $damageBuilding + $damageEquipment + $damageMaterial;

        $district = District::inRandomOrder()->first() ?? District::factory()->create();
        $affiliation = Affiliation::inRandomOrder()->first() ?? Affiliation::factory()->create();

        $schoolPrefixes = ['โรงเรียนบ้าน', 'โรงเรียนวัด', 'โรงเรียนชุมชน', 'โรงเรียน', 'วิทยาลัย'];
        $locations = ['ยะลา', 'เบตง', 'รามัน', 'บันนังสตา', 'ธารโต', 'กาบัง', 'กรงปินัง', 'ยะหา', 'ลำพะยา', 'โกตาบารู', 'สะเตง', 'ท่าสาป'];
        $suffixes = ['', 'วิทยา', 'พัฒนา', 'สามัคคี', 'มิตรภาพที่ ' . $this->faker->numberBetween(100, 200), 'อนุสรณ์'];
        
        $schoolName = $this->faker->randomElement($schoolPrefixes) . $this->faker->randomElement($locations) . $this->faker->randomElement($suffixes);

        $base = [
            'reported_at' => $reportedAt->format('Y-m-d H:i:s'),
            'disaster_type' => 'น้ำท่วม',
            'organization_name' => $schoolName,
            'district_id' => $district->id,
            'affiliation_id' => $affiliation->id,
            'current_status' => $this->faker->randomElement(['ปกติ', 'เฝ้าระวัง', 'ต้องการความช่วยเหลือเร่งด่วน']),
            'teaching_status' => $this->faker->randomElement(['open', 'closed']),
            'affected_students' => $this->faker->numberBetween(0, 1200),
            'injured_students' => $this->faker->numberBetween(0, 50),
            'dead_students' => $this->faker->numberBetween(0, 5),
            'dead_students_list' => null,
            'affected_staff' => $this->faker->numberBetween(0, 500),
            'injured_staff' => $this->faker->numberBetween(0, 20),
            'dead_staff' => $this->faker->numberBetween(0, 3),
            'dead_staff_list' => null,
            'damage_building' => number_format($damageBuilding, 2, '.', ''),
            'damage_equipment' => number_format($damageEquipment, 2, '.', ''),
            'damage_material' => number_format($damageMaterial, 2, '.', ''),
            'damage_total_request' => number_format($damageTotal, 2, '.', ''),
            'assistance_received' => $this->faker->optional()->sentence(),
            'contact_name' => $this->faker->name(),
            'contact_position' => 'Director',
            'is_published' => $this->faker->boolean(50),

            'contact_phone' => $this->faker->phoneNumber(),
            // Yala Coordinates approx: Lat 5.7-6.6, Long 101.0-101.6
            'latitude' => $this->faker->latitude(5.750000, 6.550000),
            'longitude' => $this->faker->longitude(101.100000, 101.450000),
        ];

        $hashPayload = [
            $base['reported_at'],
            $base['disaster_type'],
            $base['organization_name'],
            $base['district_id'],
            $base['affiliation_id'],
            $base['current_status'],
            $base['teaching_status'],
            $base['damage_total_request'],
        ];

        $base['form_hash'] = hash('sha256', json_encode($hashPayload, JSON_THROW_ON_ERROR));

        return $base;
    }
}
