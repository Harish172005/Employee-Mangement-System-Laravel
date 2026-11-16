<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class GenerateEmployeeReport extends Command
{
    protected $signature = 'ems:generate-report';

    protected $description = 'Generate daily employee statistics report';

    public function handle(): int
    {
        // Generate employee statistics
        $totalEmployees = Employee::withTrashed()->count();

        $activeEmployees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->count();

        $inactiveEmployees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'inactive');
        })->count();

        $newEmployees = Employee::whereDate(
            'created_at',
            now()->toDateString()
        )->count();

        $totalDepartments = Department::count();

        // Prepare report
        $report = "
========================================
       EMPLOYEE MANAGEMENT REPORT
========================================
Generated At: " . now()->format('d-m-Y h:i:s A') . "

Total Employees: {$totalEmployees}
Active Employees: {$activeEmployees}
Inactive Employees: {$inactiveEmployees}
New Employees Today: {$newEmployees}
Total Departments: {$totalDepartments}

========================================
";

        // Save report to log file
        $reportLogger = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/employee-report.log'),
        ]);

        $reportLogger->info($report);

        $this->info('Employee report saved successfully.');

        // Send report through email
        $recipient = config('mail.report_to');

        if (!$recipient) {
            $this->error('Report email recipient is not configured.');

            return self::FAILURE;
        }

        try {
            Mail::raw($report, function ($message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Daily Employee Management Report');
            });

            $this->info('Employee report emailed successfully.');

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error('Failed to send employee report email.');

            Log::error('Employee report email failed', [
                'error' => $e->getMessage(),
            ]);

            return self::FAILURE;
        }
    }
}
