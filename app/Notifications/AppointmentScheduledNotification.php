<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentScheduledNotification extends Notification
{
    use Queueable;

    public Appointment $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
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
        $bloodRequest = $this->appointment->bloodRequest;
        $dateFormatted = $this->appointment->appointment_date ? $this->appointment->appointment_date->format('M d, Y') : '';
        $timeFormatted = $this->appointment->appointment_time ? date('h:i A', strtotime($this->appointment->appointment_time)) : '';

        return [
            'type'             => 'appointment_scheduled',
            'appointment_id'   => $this->appointment->id,
            'blood_request_id' => $this->appointment->blood_request_id,
            'title'            => 'Donation Appointment Scheduled',
            'patient_name'     => $bloodRequest ? $bloodRequest->patient_name : 'Recipient',
            'blood_group'      => $bloodRequest ? $bloodRequest->blood_group : '',
            'priority'         => $bloodRequest ? $bloodRequest->priority : 'normal',
            'location'         => $this->appointment->location,
            'units_required'   => $bloodRequest ? $bloodRequest->units_required : 1,
            'needed_by_date'   => $this->appointment->appointment_date ? $this->appointment->appointment_date->format('Y-m-d') : null,
            'appointment_date' => $this->appointment->appointment_date ? $this->appointment->appointment_date->format('Y-m-d') : null,
            'appointment_time' => $this->appointment->appointment_time,
            'message'          => "The recipient has scheduled a donation appointment with you on {$dateFormatted} at {$timeFormatted} at {$this->appointment->location}.",
        ];
    }
}

