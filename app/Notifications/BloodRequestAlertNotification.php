<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BloodRequestAlertNotification extends Notification
{
    use Queueable;

    public BloodRequest $bloodRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'blood_request_id' => $this->bloodRequest->id,
            'title'            => 'New Blood Donation Request',
            'patient_name'     => $this->bloodRequest->patient_name,
            'blood_group'      => $this->bloodRequest->blood_group,
            'priority'         => $this->bloodRequest->priority,
            'location'         => $this->bloodRequest->location,
            'units_required'   => $this->bloodRequest->units_required,
            'needed_by_date'   => $this->bloodRequest->needed_by_date instanceof \DateTimeInterface
                                    ? $this->bloodRequest->needed_by_date->format('Y-m-d')
                                    : (string) $this->bloodRequest->needed_by_date,
        ];
    }
}
