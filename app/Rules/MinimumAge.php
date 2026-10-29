<?php

namespace App\Rules;

use Illuminate\Support\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use InvalidArgumentException;

class MinimumAge implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || trim($value) === '') {
            return;
        }

        try {
            $dateOfBirth = Carbon::parse($value);
        } catch (InvalidArgumentException) {
            return;
        }

        if ($dateOfBirth->isAfter(Carbon::today()->subYears(18))) {
            $fail('The employee must be at least 18 years old.');
        }
    }
}
