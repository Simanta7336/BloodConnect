<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\DonationResponse;
use App\Models\User;
use App\Notifications\AppointmentScheduledNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AppointmentScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected User $recipient;
    protected User $donor;
    protected BloodRequest $bloodRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recipient = User::factory()->create([
            'role' => 'recipient',
            'name' => 'John Recipient',
            'email' => 'recipient@example.com',
        ]);

        $this->donor = User::factory()->create([
            'role' => 'donor',
            'name' => 'Jane Donor',
            'email' => 'donor@example.com',
            'blood_group' => 'O+',
            'is_available' => true,
        ]);

        $this->bloodRequest = BloodRequest::create([
            'user_id' => $this->recipient->id,
            'patient_name' => 'Alice Smith',
            'blood_group' => 'O+',
            'location' => 'Dhaka Medical College Hospital',
            'units_required' => 2,
            'needed_by_date' => now()->addDays(3)->toDateString(),
            'priority' => 'urgent',
            'status' => 'accepted',
        ]);

        DonationResponse::create([
            'blood_request_id' => $this->bloodRequest->id,
            'donor_id' => $this->donor->id,
            'status' => 'accepted',
        ]);
    }

    public function test_recipient_can_view_appointment_schedule_form_for_accepted_request(): void
    {
        $response = $this->actingAs($this->recipient)
            ->get(route('appointments.create', $this->bloodRequest->id));

        $response->assertStatus(200);
        $response->assertSee('Schedule Donation');
        $response->assertSee('Alice Smith');
        $response->assertSee('Jane Donor');
    }

    public function test_recipient_cannot_view_schedule_form_for_pending_request(): void
    {
        $pendingRequest = BloodRequest::create([
            'user_id' => $this->recipient->id,
            'patient_name' => 'Bob',
            'blood_group' => 'A+',
            'location' => 'General Hospital',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->toDateString(),
            'priority' => 'normal',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->recipient)
            ->get(route('appointments.create', $pendingRequest->id));

        $response->assertRedirect(route('blood-requests.show', $pendingRequest->id));
        $response->assertSessionHas('error');
    }

    public function test_donor_cannot_access_schedule_form(): void
    {
        $response = $this->actingAs($this->donor)
            ->get(route('appointments.create', $this->bloodRequest->id));

        $response->assertStatus(403);
    }

    public function test_other_recipient_cannot_schedule_appointment_for_request(): void
    {
        $otherRecipient = User::factory()->create(['role' => 'recipient']);

        $response = $this->actingAs($otherRecipient)
            ->get(route('appointments.create', $this->bloodRequest->id));

        $response->assertStatus(403);
    }

    public function test_recipient_can_successfully_schedule_appointment(): void
    {
        Notification::fake();

        $appointmentDate = now()->addDays(2)->format('Y-m-d');
        $appointmentTime = '14:30';

        $response = $this->actingAs($this->recipient)->post(route('appointments.store'), [
            'blood_request_id' => $this->bloodRequest->id,
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'location' => 'Dhaka Medical Blood Bank, Room 204',
            'notes' => 'Please arrive 15 minutes early and bring ID.',
        ]);

        $this->assertDatabaseHas('appointments', [
            'blood_request_id' => $this->bloodRequest->id,
            'recipient_id' => $this->recipient->id,
            'donor_id' => $this->donor->id,
            'location' => 'Dhaka Medical Blood Bank, Room 204',
            'status' => 'confirmed',
        ]);

        $appointment = Appointment::where('blood_request_id', $this->bloodRequest->id)->first();
        $this->assertNotNull($appointment);

        $response->assertRedirect(route('appointments.show', $appointment->id));
        $response->assertSessionHas('status', 'appointment-created');

        Notification::assertSentTo(
            $this->donor,
            AppointmentScheduledNotification::class,
            function ($notification) use ($appointment) {
                return $notification->appointment->id === $appointment->id;
            }
        );
    }

    public function test_cannot_schedule_appointment_with_past_date(): void
    {
        $response = $this->actingAs($this->recipient)->post(route('appointments.store'), [
            'blood_request_id' => $this->bloodRequest->id,
            'appointment_date' => now()->subDay()->format('Y-m-d'),
            'appointment_time' => '10:00',
            'location' => 'Hospital Room 1',
        ]);

        $response->assertSessionHasErrors('appointment_date');
        $this->assertDatabaseMissing('appointments', [
            'blood_request_id' => $this->bloodRequest->id,
        ]);
    }

    public function test_both_recipient_and_donor_can_view_scheduled_appointment(): void
    {
        $appointment = Appointment::create([
            'blood_request_id' => $this->bloodRequest->id,
            'recipient_id' => $this->recipient->id,
            'donor_id' => $this->donor->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '11:00',
            'location' => 'Main Hospital Lab',
            'status' => 'confirmed',
        ]);

        // Recipient can view
        $recipientResponse = $this->actingAs($this->recipient)
            ->get(route('appointments.show', $appointment->id));
        $recipientResponse->assertStatus(200);
        $recipientResponse->assertSee('Donation Appointment');
        $recipientResponse->assertSee('Main Hospital Lab');

        // Donor can view
        $donorResponse = $this->actingAs($this->donor)
            ->get(route('appointments.show', $appointment->id));
        $donorResponse->assertStatus(200);
        $donorResponse->assertSee('Donation Appointment');
        $donorResponse->assertSee('Main Hospital Lab');
    }

    public function test_unrelated_user_cannot_view_appointment(): void
    {
        $appointment = Appointment::create([
            'blood_request_id' => $this->bloodRequest->id,
            'recipient_id' => $this->recipient->id,
            'donor_id' => $this->donor->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '11:00',
            'location' => 'Main Hospital Lab',
            'status' => 'confirmed',
        ]);

        $unrelatedUser = User::factory()->create(['role' => 'donor']);

        $response = $this->actingAs($unrelatedUser)
            ->get(route('appointments.show', $appointment->id));

        $response->assertStatus(403);
    }

    public function test_involved_user_can_complete_appointment(): void
    {
        $appointment = Appointment::create([
            'blood_request_id' => $this->bloodRequest->id,
            'recipient_id' => $this->recipient->id,
            'donor_id' => $this->donor->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '11:00',
            'location' => 'Main Hospital Lab',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->recipient)
            ->post(route('appointments.complete', $appointment->id));

        $response->assertRedirect(route('appointments.show', $appointment->id));
        $this->assertEquals('completed', $appointment->fresh()->status);
        $this->assertEquals('fulfilled', $this->bloodRequest->fresh()->status);
        $this->assertNotNull($this->donor->fresh()->last_donation_date);
    }

    public function test_involved_user_can_cancel_appointment(): void
    {
        $appointment = Appointment::create([
            'blood_request_id' => $this->bloodRequest->id,
            'recipient_id' => $this->recipient->id,
            'donor_id' => $this->donor->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '11:00',
            'location' => 'Main Hospital Lab',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->recipient)
            ->post(route('appointments.cancel', $appointment->id));

        $response->assertRedirect(route('appointments.show', $appointment->id));
        $this->assertEquals('cancelled', $appointment->fresh()->status);
    }
}

