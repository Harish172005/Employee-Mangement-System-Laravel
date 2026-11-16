<?php

namespace App\Observers;

use App\Models\Department;

class DepartmentObserver
{
    public function created(Department $department): void
    {
        $this->log($department, 'created', "Department {$department->name} was created");
    }

    public function updated(Department $department): void
    {
        $changed = collect($department->getChanges())->except('updated_at')->keys();

        if ($changed->isNotEmpty()) {
            $this->log($department, 'updated', 'Changed: ' . $changed->implode(', '));
        }
    }

    private function log(Department $department, string $action, string $description): void
    {
        $department->activityLogs()->create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'description' => $description,
        ]);
    }
}
