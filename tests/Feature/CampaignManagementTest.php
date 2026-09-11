<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $donor;
    protected User $recipient;
    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'System Admin',
            'email' => 'admin@example.com',
        ]);

        $this->donor = User::factory()->create([
            'role' => 'donor',
            'name' => 'John Donor',
            'email' => 'donor@example.com',
        ]);

        $this->recipient = User::factory()->create([
            'role' => 'recipient',
            'name' => 'Mary Recipient',
            'email' => 'recipient@example.com',
        ]);

        $this->campaign = Campaign::create([
            'title' => 'City Blood Drive 2026',
            'description' => 'Annual community blood donation event to help regional hospitals.',
            'campaign_date' => now()->addDays(10)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'location' => 'Central Community Center, Dhaka',
            'organizer' => 'Red Crescent & BloodConnect',
            'contact' => '+8801700000000',
            'status' => 'upcoming',
        ]);
    }

    public function test_authenticated_users_can_view_campaigns_list(): void
    {
        $response = $this->actingAs($this->donor)->get(route('campaigns.index'));

        $response->assertStatus(200);
        $response->assertSee('Blood Donation Campaigns');
        $response->assertSee('City Blood Drive 2026');
        $response->assertDontSee('Create Campaign'); // Donor should not see Create button
    }

    public function test_admin_sees_create_button_on_campaigns_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('campaigns.index'));

        $response->assertStatus(200);
        $response->assertSee('Create Campaign');
    }

    public function test_authenticated_users_can_view_single_campaign(): void
    {
        $response = $this->actingAs($this->recipient)->get(route('campaigns.show', $this->campaign->id));

        $response->assertStatus(200);
        $response->assertSee('City Blood Drive 2026');
        $response->assertSee('Central Community Center, Dhaka');
        $response->assertDontSee('Edit'); // Recipient should not see edit button
        $response->assertDontSee('Delete');
    }

    public function test_admin_sees_edit_and_delete_on_campaign_details(): void
    {
        $response = $this->actingAs($this->admin)->get(route('campaigns.show', $this->campaign->id));

        $response->assertStatus(200);
        $response->assertSee('Edit');
        $response->assertSee('Delete');
    }

    public function test_admin_can_view_create_campaign_screen(): void
    {
        $response = $this->actingAs($this->admin)->get(route('campaigns.create'));

        $response->assertStatus(200);
        $response->assertSee('Create Blood Donation Campaign');
    }

    public function test_non_admin_cannot_view_create_campaign_screen(): void
    {
        $response = $this->actingAs($this->donor)->get(route('campaigns.create'));

        $response->assertStatus(403);
    }

    public function test_admin_can_store_new_campaign(): void
    {
        $campaignData = [
            'title' => 'Emergency Monsoon Blood Drive',
            'description' => 'Urgent blood collection campaign for dengue relief.',
            'campaign_date' => now()->addDays(5)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '16:00',
            'location' => 'University Medical Hall',
            'organizer' => 'Student Welfare Society',
            'contact' => '+8801811111111',
            'status' => 'upcoming',
        ];

        $response = $this->actingAs($this->admin)->post(route('campaigns.store'), $campaignData);

        $response->assertRedirect(route('campaigns.index'));
        $response->assertSessionHas('status', 'campaign-created');

        $this->assertDatabaseHas('campaigns', [
            'title' => 'Emergency Monsoon Blood Drive',
            'location' => 'University Medical Hall',
        ]);
    }

    public function test_non_admin_cannot_store_campaign(): void
    {
        $campaignData = [
            'title' => 'Unauthorized Drive',
            'description' => 'Unauthorized campaign',
            'campaign_date' => now()->addDays(5)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '16:00',
            'location' => 'Some location',
            'organizer' => 'Unknown',
            'contact' => '123456',
            'status' => 'upcoming',
        ];

        $response = $this->actingAs($this->donor)->post(route('campaigns.store'), $campaignData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('campaigns', [
            'title' => 'Unauthorized Drive',
        ]);
    }

    public function test_admin_can_view_edit_campaign_screen(): void
    {
        $response = $this->actingAs($this->admin)->get(route('campaigns.edit', $this->campaign->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Campaign');
    }

    public function test_non_admin_cannot_view_edit_campaign_screen(): void
    {
        $response = $this->actingAs($this->donor)->get(route('campaigns.edit', $this->campaign->id));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_campaign(): void
    {
        $updateData = [
            'title' => 'Updated Blood Drive 2026',
            'description' => 'Updated description for regional event.',
            'campaign_date' => now()->addDays(12)->toDateString(),
            'start_time' => '08:30',
            'end_time' => '18:00',
            'location' => 'City Convention Hall',
            'organizer' => 'BloodConnect Global',
            'contact' => '+8801999999999',
            'status' => 'ongoing',
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('campaigns.update', $this->campaign->id), $updateData);

        $response->assertRedirect(route('campaigns.show', $this->campaign->id));
        $response->assertSessionHas('status', 'campaign-updated');

        $this->assertDatabaseHas('campaigns', [
            'id' => $this->campaign->id,
            'title' => 'Updated Blood Drive 2026',
            'status' => 'ongoing',
        ]);
    }

    public function test_non_admin_cannot_update_campaign(): void
    {
        $response = $this->actingAs($this->recipient)
            ->put(route('campaigns.update', $this->campaign->id), [
                'title' => 'Hacked Title',
                'description' => 'Test',
                'campaign_date' => now()->toDateString(),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'location' => 'Loc',
                'organizer' => 'Org',
                'contact' => '123',
                'status' => 'cancelled',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('campaigns', [
            'title' => 'Hacked Title',
        ]);
    }

    public function test_admin_can_delete_campaign(): void
    {
        $response = $this->actingAs($this->admin)
            ->delete(route('campaigns.destroy', $this->campaign->id));

        $response->assertRedirect(route('campaigns.index'));
        $response->assertSessionHas('status', 'campaign-deleted');

        $this->assertDatabaseMissing('campaigns', [
            'id' => $this->campaign->id,
        ]);
    }

    public function test_non_admin_cannot_delete_campaign(): void
    {
        $response = $this->actingAs($this->donor)
            ->delete(route('campaigns.destroy', $this->campaign->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('campaigns', [
            'id' => $this->campaign->id,
        ]);
    }
}

