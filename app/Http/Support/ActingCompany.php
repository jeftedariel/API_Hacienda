<?php

namespace App\Http\Support;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;

/**
 * Resuelve la empresa sobre la que actúa el principal autenticado por
 * Sanctum, sea un usuario master (dueño) o un sub-usuario de empresa.
 */
class ActingCompany
{
    public static function for(mixed $principal): ?Company
    {
        if ($principal instanceof CompanyUser) {
            return Company::find($principal->company_id);
        }

        if ($principal instanceof User) {
            // Convención de la migración: companies.id == users.id del dueño.
            return Company::where('owner_user_id', $principal->id)->first()
                ?? Company::find($principal->id);
        }

        return null;
    }

    public static function idFor(mixed $principal): ?int
    {
        return self::for($principal)?->id;
    }
}
