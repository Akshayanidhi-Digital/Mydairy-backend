<?php

namespace App\Rules;

use App\Models\DealerRolePermissions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PermissionExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $key = explode('.', $attribute)[1];
        $exists = DealerRolePermissions::where('permission_id', $key)->exists();

        if (!$exists) {
            $fail('The permission key ' . $key . ' is invalid.');
        }
    }
}
