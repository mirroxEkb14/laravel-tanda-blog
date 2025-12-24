<?php

namespace App\Enums;

enum InstitutionType: string
{
    case School = 'school';
    case Kindergarten = 'kindergarten';
    case College = 'college';
    case University = 'university';
    case LanguageCenter = 'language_center';

    public function label(): string
    {
        return match ($this) {
            self::School => 'School',
            self::Kindergarten => 'Kindergarten',
            self::College => 'College',
            self::University => 'University',
            self::LanguageCenter => 'Language center',
        };
    }

    /**
     * Gets all options as an associative array converting enum values to labels (['school' => 'School', ...])
     */
    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->label();
        }
        return $out;
    }
}
