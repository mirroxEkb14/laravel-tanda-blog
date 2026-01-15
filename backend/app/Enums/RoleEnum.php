<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'admin';
    case SuperAdmin = 'super admin';
    case User = 'user';

    /**
     * @return array<int, string>
     */
    public static function adminRoles(): array
    {
        return [
            self::Admin->value,
            self::SuperAdmin->value,
        ];
    }
}
