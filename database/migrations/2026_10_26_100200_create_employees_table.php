<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 20)->unique();

            // Login account for this employee (optional, one account per employee).
            // If the user account is deleted, keep the HR record and set user_id to NULL.
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();

            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email')->unique();
            $table->string('phone', 15);
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_joining');

            // Department (1) -> Employee (Many).
            // RESTRICT: a department that still has employees cannot be deleted.
            $table->foreignId('department_id')->constrained()->restrictOnDelete();

            $table->string('designation', 100);
            $table->decimal('salary', 10, 2);
            $table->text('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();   // created_at, updated_at
            $table->softDeletes();  // deleted_at (deactivate without losing data)

            $table->index(['last_name', 'first_name']);   // speeds up name search / sort
            $table->index('status');                      // speeds up status filter
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
