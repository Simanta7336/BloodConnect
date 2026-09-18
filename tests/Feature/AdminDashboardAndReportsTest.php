<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardAndReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard_or_reports(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.reports.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_admin_dashboard_or_reports(): void
    {
        $donor = User::factory()->create(['role' => 'donor']);
        $recipient = User::factory()->create(['role' => 'recipient']);
        $hospitalUser = User::factory()->create(['role' => 'hospital']);

        $this->actingAs($donor)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($donor)->get(route('admin.reports.index'))->assertStatus(403);

        $this->actingAs($recipient)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($recipient)->get(route('admin.reports.index'))->assertStatus(403);

        $this->actingAs($hospitalUser)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($hospitalUser)->get(route('admin.reports.index'))->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_see_platform_stats(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'donor', 'blood_group' => 'A+']);
        User::factory()->create(['role' => 'recipient']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertSee('Admin Dashboard');
        $response->assertSee('User Overview');
        $response->assertSee('Blood Request Overview');
    }

    public function test_admin_can_verify_and_reject_hospital(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hospitalUser = User::factory()->create(['role' => 'hospital']);
        $hospital = Hospital::create([
            'user_id' => $hospitalUser->id,
            'hospital_name' => 'City General Hospital',
            'license_number' => 'MED-12345',
            'is_verified' => false,
        ]);

        $this->assertFalse($hospital->fresh()->is_verified);

        // Verify hospital
        $response = $this->actingAs($admin)->post(route('admin.hospitals.verify', $hospital->id));
        $response->assertSessionHas('status', 'hospital-verified');
        $this->assertTrue($hospital->fresh()->is_verified);

        // Revoke hospital verification
        $response = $this->actingAs($admin)->post(route('admin.hospitals.revoke', $hospital->id));
        $response->assertSessionHas('status', 'hospital-revoked');
        $this->assertFalse($hospital->fresh()->is_verified);

        // Reject and delete hospital
        $response = $this->actingAs($admin)->post(route('admin.hospitals.reject', $hospital->id));
        $response->assertSessionHas('status', 'hospital-rejected');
        $this->assertDatabaseMissing('hospitals', ['id' => $hospital->id]);
    }

    public function test_admin_can_access_reports_and_see_blood_group_statistics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $recipient = User::factory()->create(['role' => 'recipient']);

        User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);
        User::factory()->create(['role' => 'donor', 'blood_group' => 'B+', 'is_available' => true]);

        BloodRequest::create([
            'user_id' => $recipient->id,
            'patient_name' => 'Jane Doe',
            'blood_group' => 'O+',
            'location' => 'Dhaka',
            'units_required' => 2,
            'needed_by_date' => now()->addDays(2),
            'priority' => 'urgent',
            'status' => 'fulfilled',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports');
        $response->assertSee('Reports &amp; Statistics', false);
        $response->assertSee('Blood Group Summary Table');
        $response->assertSee('Donors by Blood Group');
    }
}