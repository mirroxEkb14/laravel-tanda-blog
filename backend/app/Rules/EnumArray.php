<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that input value is an array of valid enum values
 */
class EnumArray implements ValidationRule
{
    /**
     * @param  class-string<\BackedEnum>  $enumClass
     */
    public function __construct(private string $enumClass)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (! is_array($value)) {
            $fail("The {$attribute} must be an array");
            return;
        }

        $allowed = array_map(fn ($c) => $c->value, ($this->enumClass)::cases());

        foreach ($value as $item) {
            if (! is_string($item) || ! in_array($item, $allowed, true)) {
                $fail("The {$attribute} contains an invalid value");
                return;
            }
        }
    }
}
