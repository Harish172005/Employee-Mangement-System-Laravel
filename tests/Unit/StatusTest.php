<?php

namespace Tests\Unit;

use App\Enums\Gender;
use App\Enums\Role;
use App\Enums\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function test_status_enum_contains_the_supported_account_states(): void
    {
        $this->assertSame(['active', 'inactive'], array_column(Status::cases(), 'value'));
    }

    public function test_role_enum_contains_the_supported_roles(): void
    {
        $this->assertSame(['admin', 'employee'], array_column(Role::cases(), 'value'));
    }

    public function test_gender_enum_contains_the_supported_values(): void
    {
        $this->assertSame(['male', 'female', 'other'], array_column(Gender::cases(), 'value'));
    }
}
