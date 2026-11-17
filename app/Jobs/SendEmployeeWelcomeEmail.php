<?php

namespace App\Jobs;

use App\Mail\EmployeeWelcomeMail;
use App\Models\Employee;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmployeeWelcomeEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employee $employee
    ) {}

    public function handle(): void
    {
        Mail::to($this->employee->email)
            ->send(new EmployeeWelcomeMail($this->employee));
    }
}
