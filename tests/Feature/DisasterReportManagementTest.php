<?php

namespace Tests\Feature;

use App\Models\Affiliation;
use App\Models\DisasterReport;
use App\Models\District;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisasterReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_entry_user_can_create_disaster_report(): void
    {
        $user = User::factory()->create(['role' => 'data-entry']);
        $district = District::factory()->create();
        $affiliation = Affiliation::factory()->create();

        $payload = [
            'reported_at' => now()->format('Y-m-d\TH:i'),
            'disaster_type' => 'น้ำท่วม',
            'organization_name' => 'โรงเรียนทดสอบ',
            'district_id' => $district->id,
            'affiliation_id' => $affiliation->id,
            'current_status' => 'ต้องการความช่วยเหลือเร่งด่วน',
            'teaching_status' => 'closed',
            'affected_students' => 120,
            'injured_students' => 4,
            'dead_students' => 0,
            'dead_students_list' => null,
            'affected_staff' => 30,
            'injured_staff' => 1,
            'dead_staff' => 0,
            'dead_staff_list' => null,
            'damage_building' => '1500000.00',
            'damage_equipment' => '500000.00',
            'damage_material' => '250000.00',
            'damage_total_request' => '2250000.00',
            'assistance_received' => 'ได้รับถุงยังชีพจาก อบต.',
            'contact_name' => 'นายอาสาสมัคร ทดสอบ',
            'contact_position' => 'ผู้อำนวยการโรงเรียน',
            'contact_phone' => '0801234567',
            'latitude' => '6.5410000',
            'longitude' => '101.2810000',
        ];

        $response = $this->actingAs($user)->post(route('disaster.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('disaster_reports', [
            'organization_name' => 'โรงเรียนทดสอบ',
            'district_id' => $district->id,
            'affiliation_id' => $affiliation->id,
            'teaching_status' => 'closed',
        ]);
    }

    public function test_duplicate_submission_is_prevented_with_hash_validation(): void
    {
        $user = User::factory()->create(['role' => 'data-entry']);
        $district = District::factory()->create();
        $affiliation = Affiliation::factory()->create();

        $payload = [
            'reported_at' => now()->format('Y-m-d\TH:i'),
            'disaster_type' => 'ไฟไหม้',
            'organization_name' => 'โรงเรียนซ้ำ',
            'district_id' => $district->id,
            'affiliation_id' => $affiliation->id,
            'current_status' => 'เฝ้าระวัง',
            'teaching_status' => 'open',
            'affected_students' => 10,
            'injured_students' => 0,
            'dead_students' => 0,
            'dead_students_list' => null,
            'affected_staff' => 3,
            'injured_staff' => 0,
            'dead_staff' => 0,
            'dead_staff_list' => null,
            'damage_building' => '100000.00',
            'damage_equipment' => '50000.00',
            'damage_material' => '20000.00',
            'damage_total_request' => '170000.00',
            'assistance_received' => null,
            'contact_name' => 'นายตรวจสอบ',
            'contact_position' => 'ครูผู้ช่วย',
            'contact_phone' => '0800000000',
            'latitude' => '6.5000000',
            'longitude' => '101.2000000',
        ];

        $this->actingAs($user)->post(route('disaster.store'), $payload)->assertRedirect();

        $this->actingAs($user)
            ->from(route('disaster.create'))
            ->post(route('disaster.store'), $payload)
            ->assertSessionHasErrors('form_hash');

        $this->assertDatabaseCount('disaster_reports', 1);
    }

    public function test_viewer_role_cannot_access_mutating_routes(): void
    {
        $viewer = User::factory()->create(['role' => 'viewer']);
        $district = District::factory()->create();
        $affiliation = Affiliation::factory()->create();
        $report = DisasterReport::factory()->create([
            'district_id' => $district->id,
            'affiliation_id' => $affiliation->id,
        ]);

        $this->actingAs($viewer)
            ->get(route('disaster.create'))
            ->assertStatus(403);

        $this->actingAs($viewer)
            ->delete(route('disaster.destroy', $report))
            ->assertStatus(403);
    }
}
