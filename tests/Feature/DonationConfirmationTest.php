<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\DonationResponse;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected User $hospitalUser;
    protected Hospital $hospital;
    protected User $admin;
    protected User $donor;
    protected User $recipient;
    protected BloodRequest $bloodRequest;
    protected DonationResponse $donationResponse;
    protected Appointment $appointment;

    protected function setUp(): void
    {
        parent::setUp();

        // Hospital User & Profile
        $this->hospitalUser = User::factory()->create([
            'role' => 'hospital',
            'name' => 'Square Hospital Staff',
            'email' => 'hospital@square.com',
        ]);

        $this->hospital = Hospital::create([
            'user_id' => $this->hospitalUser->id,
            'hospital_name' => 'Square Hospital',
            'license_number' => 'HOSP-12345',
            'is_verified' => true,
        ]);

        // Admin User
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'System Admin',
            'email' => 'admin@bloodconnect.org',
        ]);

        // Donor User
        $this->donor = User::factory()->create([
            'role' => 'donor',
            'name' => 'Rahim Ahmed',
            'email' => 'rahim@example.com',
            'blood_group' => 'O+',
            'is_available' => true,
            'last_donation_date' => null,
        ]);

        // Recipient User
        $this->recipient = User::factory()->create([
            'role' => 'recipient',
            'name' => 'Karim Ahmed',
            'email' => 'karim@example.com',
        ]);

        // Blood Request
        $this->bloodRequest = BloodRequest::create([
            'user_id' => $this->recipient->id,
            'hospital_id' => $this->hospital->id,
            'patient_name' => 'Karim Ahmed',
            'blood_group' => 'O+',
            'location' => 'Square Hospital, Dhaka',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->toDateString(),
            'priority' => 'urgent',
            'status' => 'accepted',
        ]);

        // Accepted Donation Response
        $this->donationResponse = DonationResponse::create([
            'blood_request_id' => $this->bloodRequest->id,
            'donor_id' => $this->donor->id,
            'status' => 'accepted',
        ]);

        // Scheduled Appointment
        $this->appointment = Appointment::create([
            'blood_request_id' => $this->bloodRequest->id,
            'recipient_id' => $this->recipient->id,
            'donor_id' => $this->donor->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '10:00',
            'location' => 'Square Hospital Blood Bank',
            'status' => 'confirmed',
        ]);
    }

    public function test_hospital_can_confirm_completed_donation_for_managed_request(): void
    {
        $response = $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id), [
                'confirmation_notes' => '1 unit collected successfully. Donor is stable.',
            ]);

        $response->assertSessionHas('status', 'donation-confirmed');

        // Verify DonationResponse
        $freshResponse = $this->donationResponse->fresh();
        $this->assertEquals('completed', $freshResponse->status);
        $this->assertNotNull($freshResponse->completed_at);
        $this->assertEquals($this->hospitalUser->id, $freshResponse->confirmed_by);
        $this->assertEquals('1 unit collected successfully. Donor is stable.', $freshResponse->confirmation_notes);

        // Verify BloodRequest status
        $this->assertEquals('fulfilled', $this->bloodRequest->fresh()->status);

        // Verify Appointment status
        $this->assertEquals('completed', $this->appointment->fresh()->status);

        // Verify Donor last donation date & eligibility
        $freshDonor = $this->donor->fresh();
        $this->assertEquals(now()->toDateString(), $freshDonor->last_donation_date->toDateString());
        $this->assertFalse($freshDonor->isEligibleToDonate());
    }

    public function test_hospital_can_confirm_completed_donation_for_unassigned_request_without_forced_reassignment(): void
    {
        $this->bloodRequest->update(['hospital_id' => null]);

        $response = $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertSessionHas('status', 'donation-confirmed');
        $this->assertEquals('completed', $this->donationResponse->fresh()->status);
        $this->assertEquals('fulfilled', $this->bloodRequest->fresh()->status);
        $this->assertNull($this->bloodRequest->fresh()->hospital_id);
    }

    public function test_admin_can_confirm_completed_donation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.donations.confirm', $this->donationResponse->id), [
                'confirmation_notes' => 'Admin verified completion.',
            ]);

        $response->assertSessionHas('status', 'donation-confirmed');
        $this->assertEquals('completed', $this->donationResponse->fresh()->status);
        $this->assertEquals($this->admin->id, $this->donationResponse->fresh()->confirmed_by);
        $this->assertEquals('fulfilled', $this->bloodRequest->fresh()->status);
        $this->assertEquals('completed', $this->appointment->fresh()->status);
    }

    public function test_unauthorized_hospital_cannot_confirm_donation_managed_by_another_hospital(): void
    {
        $otherHospitalUser = User::factory()->create(['role' => 'hospital']);
        Hospital::create([
            'user_id' => $otherHospitalUser->id,
            'hospital_name' => 'Other Hospital',
            'license_number' => 'HOSP-99999',
            'is_verified' => true,
        ]);

        $response = $this->actingAs($otherHospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertStatus(403);
        $this->assertEquals('accepted', $this->donationResponse->fresh()->status);
    }

    public function test_donor_cannot_confirm_donation(): void
    {
        $response = $this->actingAs($this->donor)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertStatus(403);
        $this->assertEquals('accepted', $this->donationResponse->fresh()->status);
    }

    public function test_recipient_cannot_confirm_donation(): void
    {
        $response = $this->actingAs($this->recipient)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertStatus(403);
        $this->assertEquals('accepted', $this->donationResponse->fresh()->status);
    }

    public function test_guest_cannot_confirm_donation(): void
    {
        $response = $this->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertRedirect(route('login'));
        $this->assertEquals('accepted', $this->donationResponse->fresh()->status);
    }

    public function test_cannot_confirm_already_completed_donation(): void
    {
        $this->donationResponse->update([
            'status' => 'completed',
            'completed_at' => now(),
            'confirmed_by' => $this->hospitalUser->id,
        ]);

        $response = $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertSessionHas('error');
    }

    public function test_cannot_confirm_rejected_donation(): void
    {
        $this->donationResponse->update(['status' => 'rejected']);

        $response = $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertSessionHas('error');
        $this->assertEquals('rejected', $this->donationResponse->fresh()->status);
    }

    public function test_cannot_confirm_donation_for_cancelled_blood_request(): void
    {
        $this->bloodRequest->update(['status' => 'cancelled']);

        $response = $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertSessionHas('error');
        $this->assertEquals('accepted', $this->donationResponse->fresh()->status);
    }

    public function test_cannot_confirm_donation_for_cancelled_appointment(): void
    {
        $this->appointment->update(['status' => 'cancelled']);

        $response = $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        $response->assertSessionHas('error');
        $this->assertEquals('accepted', $this->donationResponse->fresh()->status);
    }

    public function test_confirmation_works_with_blood_request_id(): void
    {
        $response = $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->bloodRequest->id));

        $response->assertSessionHas('status', 'donation-confirmed');
        $this->assertEquals('completed', $this->donationResponse->fresh()->status);
        $this->assertEquals('fulfilled', $this->bloodRequest->fresh()->status);
    }

    public function test_completed_donation_appears_in_donation_history_for_donor_and_recipient(): void
    {
        $this->actingAs($this->hospitalUser)
            ->post(route('hospital.donations.confirm', $this->donationResponse->id));

        // Check Donor donation history
        $donorHistoryResponse = $this->actingAs($this->donor)
            ->get(route('donation-history.index'));
        $donorHistoryResponse->assertStatus(200);
        $donorHistoryResponse->assertSee('Karim Ahmed');
        $donorHistoryResponse->assertSee('completed');

        // Check Recipient donation history
        $recipientHistoryResponse = $this->actingAs($this->recipient)
            ->get(route('donation-history.index'));
        $recipientHistoryResponse->assertStatus(200);
        $recipientHistoryResponse->assertSee('Karim Ahmed');
        $recipientHistoryResponse->assertSee('Rahim Ahmed');
        $recipientHistoryResponse->assertSee('fulfilled');
    }
}
