<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeUpdatedNotification extends Notification
{
    use Queueable;

    protected Employee $employee;
    protected string $message;

    public function __construct(Employee $employee, string $message)
    {
        $this->employee = $employee;
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Employee Profile Updated')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->message)
            ->line('Please contact the administrator if you have any questions.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'employee_id' => $this->employee->id,
            'message' => $this->message,
            'type' => 'employee_updated',
        ];
    }
}
