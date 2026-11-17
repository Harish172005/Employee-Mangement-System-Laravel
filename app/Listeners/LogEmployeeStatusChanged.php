<?php

namespace App\Listeners;

use App\Events\EmployeeStatusChanged;
use Illuminate\Support\Facades\Log;

class LogEmployeeStatusChanged
{
    public function handle(EmployeeStatusChanged $event): void
    {
        Log::info('Employee status changed.', [
            'employee_id' => $event->employee->id,
            'employee_name' => $event->employee->first_name.' '.$event->employee->last_name,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
        ]);
    }
}
