<?php

namespace App\Rules;

use App\Models\TransportDrivers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDriverForVehicle implements ValidationRule
{
    protected $transporterId;
    protected $vehicleId;

    public function __construct($transporterId, $vehicleId)
    {
        $this->transporterId = $transporterId;
        $this->vehicleId = $vehicleId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $exists = TransportDrivers::where('transporter_id', $this->transporterId)
            ->where('deleted', false)
            ->where(function ($query) {
                $query->whereDoesntHave('vehicle')
                    ->orWhere('driver_id', $this->vehicleId);
            })
            ->where('driver_id', $value)
            ->exists();

        if (!$exists) {
            $fail('The selected driver is invalid or already assigned vehicle.');
        }
    }
}
