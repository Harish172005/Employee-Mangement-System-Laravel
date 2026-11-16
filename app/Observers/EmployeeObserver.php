<?php

namespace App\Observers;

use App\Models\Employee;

/** Writes one activity_logs row whenever an employee is created, changed, deactivated or restored. */
class EmployeeObserver
{
    public function created(Employee $employee): void
    {
        $this->log($employee, 'created', "Employee {$employee->employee_code} was created");
    }

    public function updated(Employee $employee): void
    {
        $changed = collect($employee->getChanges())->except('updated_at')->keys();

        if ($changed->isNotEmpty()) {
            $this->log($employee, 'updated', 'Changed: ' . $changed->implode(', '));
        }
    }

    public function deleted(Employee $employee): void
    {
        if ($employee->isForceDeleting()) {
            $this->log($employee, 'deleted', "Employee {$employee->employee_code} was deleted permanently");
        } else {
            $this->log($employee, 'deactivated', "Employee {$employee->employee_code} was deactivated");
        }
    }

    public function restored(Employee $employee): void
    {
        $this->log($employee, 'restored', "Employee {$employee->employee_code} was restored");
    }

    private function log(Employee $employee, string $action, string $description): void
    {
        $employee->activityLogs()->create([
            'user_id'     => auth()->id(),   // null until login exists (Module 8) or when seeding
            'action'      => $action,
            'description' => $description,
        ]);
    }
}
