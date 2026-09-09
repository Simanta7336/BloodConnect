<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\DonationResponse;
use App\Models\User;
use App\Notifications\BloodRequestAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcceptRejectDonationRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create a recipient and a blood request.
     */
    protected function createBloodRequest(array $attributes = []): BloodRequest
    {
        $recipient = User::factory()->create([
            'role'     => 'recipient',
            'phone'    => '01711223344',
            'location' => 'Dhaka Medical Area',
        ]);

        return BloodRequest::create(array_merge([
            'user_id'        => $recipient->id,
            'patient_name'   => 'Test Patient',
            'blood_group'    => 'O+',
            'location'       => 'Dhaka Medical College Hospital',
            'units_required' => 2,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'emergency',
            'notes'          => 'Urgent transfusion needed',
            'status'         => 'pending',
        ], $attributes));
    }

    public function test_eligible_notified_donor_can_view_request(): void
    {
        $request = $this->createBloodRequest();
        $donor = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        // Send F12 notification to donor
        $donor->notify(new BloodRequestAlertNotification($request));

        $response = $this->actingAs($donor)->get("/blood-requests/{$request->id}");

        $response->assertStatus(200);
        $response->assertSee('Test Patient');
        $response->assertSee('Dhaka Medical College Hospital');
        $response->assertSee('Emergency');
        $response->assertSee('Accept Donation');
        $response->assertSee('Decline / Reject');
        // Recipient contact number should NOT be shown before acceptance
        $response->assertDontSee('01711223344');
    }

    public function test_donor_cannot_view_or_respond_without_receiving_notification(): void
    {
        $request = $this->createBloodRequest();
        $unnotifiedDonor = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        // Unnotified donor tries to view
        $viewResponse = $this->actingAs($unnotifiedDonor)->get("/blood-requests/{$request->id}");
        $viewResponse->assertStatus(403);

        // Unnotified donor tries to accept
        $acceptResponse = $this->actingAs($unnotifiedDonor)->post("/blood-requests/{$request->id}/accept");
        $acceptResponse->assertStatus(403);

        // Unnotified donor tries to reject
        $rejectResponse = $this->actingAs($unnotifiedDonor)->post("/blood-requests/{$request->id}/reject");
        $rejectResponse->assertStatus(403);
    }

    public function test_eligible_notified_donor_can_accept(): void
    {
        $request = $this->createBloodRequest();
        $donor = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        $donor->notify(new BloodRequestAlertNotification($request));
        $this->assertCount(1, $donor->unreadNotifications);

        $response = $this->actingAs($donor)->post("/blood-requests/{$request->id}/accept");

        $response->assertRedirect(route('blood-requests.show', $request->id));
        $response->assertSessionHas('status', 'donation-accepted');

        // Check database response record
        $this->assertDatabaseHas('donation_responses', [
            'blood_request_id' => $request->id,
            'donor_id'         => $donor->id,
            'status'           => 'accepted',
        ]);

        // Check blood request status is updated to accepted
        $this->assertEquals('accepted', $request->fresh()->status);

        // Check notification is marked as read
        $this->assertCount(0, $donor->fresh()->unreadNotifications);
    }

    public function test_recipient_contact_information_is_shown_only_after_acceptance(): void
    {
        $request = $this->createBloodRequest();
        $donor = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        $donor->notify(new BloodRequestAlertNotification($request));

        // Accept the request
        $this->actingAs($donor)->post("/blood-requests/{$request->id}/accept");

        // Now viewing the show page reveals recipient contact information
        $response = $this->actingAs($donor)->get("/blood-requests/{$request->id}");

        $response->assertStatus(200);
        $response->assertSee('You Accepted this Donation Request');
        $response->assertSee('01711223344');
        $response->assertSee($request->user->email);
    }

    public function test_eligible_notified_donor_can_reject(): void
    {
        $request = $this->createBloodRequest();
        $donor = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        $donor->notify(new BloodRequestAlertNotification($request));

        $response = $this->actingAs($donor)->post("/blood-requests/{$request->id}/reject");

        $response->assertRedirect(route('blood-requests.show', $request->id));
        $response->assertSessionHas('status', 'donation-rejected');

        $this->assertDatabaseHas('donation_responses', [
            'blood_request_id' => $request->id,
            'donor_id'         => $donor->id,
            'status'           => 'rejected',
        ]);

        // Overall request must remain 'pending' so other donors can still accept
        $this->assertEquals('pending', $request->fresh()->status);

        // Notification marked as read
        $this->assertCount(0, $donor->fresh()->unreadNotifications);
    }

    public function test_rejecting_one_donor_does_not_close_request_for_other_donors(): void
    {
        $request = $this->createBloodRequest();
        $donor1 = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);
        $donor2 = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        $donor1->notify(new BloodRequestAlertNotification($request));
        $donor2->notify(new BloodRequestAlertNotification($request));

        // Donor 1 rejects
        $this->actingAs($donor1)->post("/blood-requests/{$request->id}/reject");
        $this->assertEquals('pending', $request->fresh()->status);

        // Donor 2 can still accept
        $response = $this->actingAs($donor2)->post("/blood-requests/{$request->id}/accept");
        $response->assertSessionHas('status', 'donation-accepted');
        $this->assertEquals('accepted', $request->fresh()->status);
    }

    public function test_donor_cannot_respond_twice_to_same_request(): void
    {
        $request = $this->createBloodRequest();
        $donor = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        $donor->notify(new BloodRequestAlertNotification($request));

        // First accept
        $this->actingAs($donor)->post("/blood-requests/{$request->id}/accept");

        // Second attempt to accept
        $secondAccept = $this->actingAs($donor)->post("/blood-requests/{$request->id}/accept");
        $secondAccept->assertSessionHas('error');

        // Attempt to reject after accepting
        $attemptReject = $this->actingAs($donor)->post("/blood-requests/{$request->id}/reject");
        $attemptReject->assertSessionHas('error');

        $this->assertCount(1, DonationResponse::where('blood_request_id', $request->id)->where('donor_id', $donor->id)->get());
    }

    public function test_second_donor_cannot_accept_already_accepted_request(): void
    {
        $request = $this->createBloodRequest();
        $donor1 = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);
        $donor2 = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        $donor1->notify(new BloodRequestAlertNotification($request));
        $donor2->notify(new BloodRequestAlertNotification($request));

        // Donor 1 accepts
        $this->actingAs($donor1)->post("/blood-requests/{$request->id}/accept");
        $this->assertEquals('accepted', $request->fresh()->status);

        // Donor 2 tries to accept
        $secondDonorResponse = $this->actingAs($donor2)->post("/blood-requests/{$request->id}/accept");
        $secondDonorResponse->assertSessionHas('error');

        // Donor 2 should not have an accepted record
        $this->assertDatabaseMissing('donation_responses', [
            'blood_request_id' => $request->id,
            'donor_id'         => $donor2->id,
        ]);

        // Viewing the page as Donor 2 shows request already accepted
        $viewResponse = $this->actingAs($donor2)->get("/blood-requests/{$request->id}");
        $viewResponse->assertSee('This blood request has already been accepted by another donor.');
    }

    public function test_recipient_cannot_use_accept_or_reject_endpoints(): void
    {
        $request = $this->createBloodRequest();
        $recipient = User::factory()->create(['role' => 'recipient']);

        $acceptResponse = $this->actingAs($recipient)->post("/blood-requests/{$request->id}/accept");
        $acceptResponse->assertStatus(403);

        $rejectResponse = $this->actingAs($recipient)->post("/blood-requests/{$request->id}/reject");
        $rejectResponse->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_endpoints(): void
    {
        $request = $this->createBloodRequest();

        $this->get("/blood-requests/{$request->id}")->assertRedirect('/login');
        $this->post("/blood-requests/{$request->id}/accept")->assertRedirect('/login');
        $this->post("/blood-requests/{$request->id}/reject")->assertRedirect('/login');
    }

    public function test_notification_view_contains_link_to_blood_request(): void
    {
        $request = $this->createBloodRequest();
        $donor = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+', 'is_available' => true]);

        $donor->notify(new BloodRequestAlertNotification($request));

        $response = $this->actingAs($donor)->get('/notifications');

        $response->assertStatus(200);
        $response->assertSee(route('blood-requests.show', $request->id));
        $response->assertSee('View Request &amp; Respond', false);
    }
}
