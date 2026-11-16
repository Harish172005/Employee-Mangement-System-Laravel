<?php

namespace Tests\Unit;

use App\Rules\MinimumAge;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_minimum_age_rule_rejects_a_person_under_eighteen(): void
    {
        Carbon::setTestNow('2026-11-12');
        $errors = [];

        try {
            (new MinimumAge)->validate(
                'date_of_birth',
                '2008-11-13',
                function (string $message) use (&$errors): void {
                    $errors[] = $message;
                }
            );
        } finally {
            Carbon::setTestNow();
        }

        $this->assertSame(['The employee must be at least 18 years old.'], $errors);
    }

    public function test_minimum_age_rule_accepts_a_person_who_is_exactly_eighteen(): void
    {
        Carbon::setTestNow('2026-11-12');
        $errors = [];

        try {
            (new MinimumAge)->validate(
                'date_of_birth',
                '2008-11-12',
                function (string $message) use (&$errors): void {
                    $errors[] = $message;
                }
            );
        } finally {
            Carbon::setTestNow();
        }

        $this->assertSame([], $errors);
    }
}
