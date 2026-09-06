<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\User;
use App\Notifications\BloodRequestAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_matching_available_donor_receives_database_notification(): void
    {
        $recipient = User::factory()->create(['role' => 'recipient']);
        
        $matchingDonor = User::factory()->create([
            'role'         => 'donor',
            'blood_group'  => 'O+',
            'is_available' => true,
            'location'     => 'Dhaka',
        ]);

        $this->actingAs($recipient)->post('/blood-requests', [
            'patient_name'   => 'John Doe',
            'blood_group'    => 'O+',
            'location'       => 'Dhaka Medical Hospital',
            'units_required' => 2,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'emergency',
            'notes'          => 'Urgent transfusion required',
        ]);

        $this->assertCount(1, $matchingDonor->notifications);
        
        $notification = $matchingDonor->notifications->first();
        $this->assertEquals('New Blood Donation Request', $notification->data['title']);
        $this->assertEquals('O+', $notification->data['blood_group']);
        $this->assertEquals('emergency', $notification->data['priority']);
        $this->assertEquals('Dhaka Medical Hospital', $notification->data['location']);
        $this->assertEquals('John Doe', $notification->data['patient_name']);
        $this->assertEquals(2, $notification->data['units_required']);
        $this->assertNotNull($notification->data['blood_request_id']);
    }

    public function test_non_matching_blood_group_donor_does_not_receive_notification(): void
    {
        $recipient = User::factory()->create(['role' => 'recipient']);
        
        $differentBloodDonor = User::factory()->create([
            'role'         => 'donor',
            'blood_group'  => 'A-',
            'is_available' => true,
        ]);

        $this->actingAs($recipient)->post('/blood-requests', [
            'patient_name'   => 'Jane Doe',
            'blood_group'    => 'B+',
            'location'       => 'Chittagong Hospital',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'urgent',
        ]);

        $this->assertCount(0, $differentBloodDonor->notifications);
    }

    public function test_unavailable_donor_does_not_receive_notification(): void
    {
        $recipient = User::factory()->create(['role' => 'recipient']);
        
        $unavailableDonor = User::factory()->create([
            'role'         => 'donor',
            'blood_group'  => 'AB+',
            'is_available' => false,
        ]);

        $this->actingAs($recipient)->post('/blood-requests', [
            'patient_name'   => 'Robert Smith',
            'blood_group'    => 'AB+',
            'location'       => 'Sylhet Clinic',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(3)->format('Y-m-d'),
            'priority'       => 'normal',
        ]);

        $this->assertCount(0, $unavailableDonor->notifications);
    }

    public function test_recipient_does_not_receive_notification(): void
    {
        $recipient1 = User::factory()->create(['role' => 'recipient']);
        
        $otherRecipient = User::factory()->create([
            'role'         => 'recipient',
            'blood_group'  => 'O-',
            'is_available' => true,
        ]);

        $this->actingAs($recipient1)->post('/blood-requests', [
            'patient_name'   => 'Patient',
            'blood_group'    => 'O-',
            'location'       => 'Rajshahi Hospital',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'emergency',
        ]);

        $this->assertCount(0, $otherRecipient->notifications);
    }

    public function test_donor_can_view_notifications_page(): void
    {
        $donor = User::factory()->create(['role' => 'donor']);
        $recipient = User::factory()->create(['role' => 'recipient']);

        $bloodRequest = BloodRequest::create([
            'user_id'        => $recipient->id,
            'patient_name'   => 'Sarah Connor',
            'blood_group'    => 'O+',
            'location'       => 'Central Hospital, Dhaka',
            'units_required' => 2,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'emergency',
            'status'         => 'pending',
        ]);

        $donor->notify(new BloodRequestAlertNotification($bloodRequest));

        $response = $this->actingAs($donor)->get('/notifications');

        $response->assertStatus(200);
        $response->assertSee('Sarah Connor');
        $response->assertSee('Emergency');
        $response->assertSee('Central Hospital, Dhaka');
        $response->assertSee('O+');
    }

    public function test_donor_can_mark_own_notification_as_read(): void
    {
        $donor = User::factory()->create(['role' => 'donor']);
        $recipient = User::factory()->create(['role' => 'recipient']);

        $bloodRequest = BloodRequest::create([
            'user_id'        => $recipient->id,
            'patient_name'   => 'Alex Morgan',
            'blood_group'    => 'A+',
            'location'       => 'City Hospital',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'urgent',
            'status'         => 'pending',
        ]);

        $donor->notify(new BloodRequestAlertNotification($bloodRequest));

        $notification = $donor->unreadNotifications->first();
        $this->assertNotNull($notification);
        $this->assertNull($notification->read_at);

        $response = $this->actingAs($donor)->post("/notifications/{$notification->id}/read");

        $response->assertSessionHas('status', 'notification-marked-read');
        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertCount(0, $donor->fresh()->unreadNotifications);
    }

    public function test_donor_cannot_mark_another_users_notification_as_read(): void
    {
        $donor1 = User::factory()->create(['role' => 'donor']);
        $donor2 = User::factory()->create(['role' => 'donor']);
        $recipient = User::factory()->create(['role' => 'recipient']);

        $bloodRequest = BloodRequest::create([
            'user_id'        => $recipient->id,
            'patient_name'   => 'Private Patient',
            'blood_group'    => 'B-',
            'location'       => 'General Hospital',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'emergency',
            'status'         => 'pending',
        ]);

        $donor1->notify(new BloodRequestAlertNotification($bloodRequest));
        $notification = $donor1->unreadNotifications->first();

        // Donor 2 attempts to mark Donor 1's notification as read
        $response = $this->actingAs($donor2)->post("/notifications/{$notification->id}/read");

        $response->assertStatus(404);
        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_donor_can_mark_all_notifications_as_read(): void
    {
        $donor = User::factory()->create(['role' => 'donor']);
        $recipient = User::factory()->create(['role' => 'recipient']);

        $request1 = BloodRequest::create([
            'user_id'        => $recipient->id,
            'patient_name'   => 'Patient 1',
            'blood_group'    => 'O+',
            'location'       => 'Hospital 1',
            'units_required' => 1,
            'needed_by_date' => now()->addDays(2)->format('Y-m-d'),
            'priority'       => 'urgent',
            'status'         => 'pending',
        ]);

        $request2 = BloodRequest::create([
            'user_id'        => $recipient->id,
            'patient_name'   => 'Patient 2',
            'blood_group'    => 'O+',
            'location'       => 'Hospital 2',
            'units_required' => 2,
            'needed_by_date' => now()->addDays(3)->format('Y-m-d'),
            'priority'       => 'emergency',
            'status'         => 'pending',
        ]);

        $donor->notify(new BloodRequestAlertNotification($request1));
        $donor->notify(new BloodRequestAlertNotification($request2));

        $this->assertCount(2, $donor->fresh()->unreadNotifications);

        $response = $this->actingAs($donor)->post('/notifications/read-all');

        $response->assertSessionHas('status', 'all-notifications-marked-read');
        $this->assertCount(0, $donor->fresh()->unreadNotifications);
    }
}
