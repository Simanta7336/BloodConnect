<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodRequestPriorityTest extends TestCase
{
    use RefreshDatabase;

    public function test_blood_request_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create(['role' => 'recipient']);

        $response = $this->actingAs($user)->get('/blood-requests/create');

        $response->assertStatus(200);
        $response->assertSee('Priority Level');
        $response->assertSee('Emergency');
        $response->assertSee('Urgent');
        $response->assertSee('Normal');
    }

    public function test_recipient_can_create_blood_request_with_emergency_priority(): void
    {
        $user = User::factory()->create(['role' => 'recipient']);

        $response = $this->actingAs($user)->post('/blood-requests', [
            'patient_name'   => 'John Doe',
            'blood_group'    => 'O+',
            'location'       => 'XYZ Hospital',
            'units_required' => 2,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'emergency',
            'notes'          => 'Critical surgery needed.',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('blood_requests', [
            'user_id'        => $user->id,
            'patient_name'   => 'John Doe',
            'blood_group'    => 'O+',
            'location'       => 'XYZ Hospital',
            'units_required' => 2,
            'priority'       => 'emergency',
            'status'         => 'pending',
        ]);
    }

    public function test_blood_request_priority_validation(): void
    {
        $user = User::factory()->create(['role' => 'recipient']);

        $response = $this->actingAs($user)->post('/blood-requests', [
            'patient_name'   => 'Jane Doe',
            'blood_group'    => 'A+',
            'location'       => 'General Hospital',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'invalid-priority',
        ]);

        $response->assertSessionHasErrors('priority');
    }

    public function test_dashboard_displays_blood_request_with_emergency_badge(): void
    {
        $user = User::factory()->create(['role' => 'recipient']);

        $request = BloodRequest::create([
            'user_id'        => $user->id,
            'patient_name'   => 'Emergency Patient',
            'blood_group'    => 'AB+',
            'location'       => 'City Emergency Center',
            'units_required' => 3,
            'needed_by_date' => now()->addDays(3)->format('Y-m-d'),
            'priority'       => 'emergency',
            'status'         => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Emergency Patient');
        $response->assertSee('Emergency');
    }
}
