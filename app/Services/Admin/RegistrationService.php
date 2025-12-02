<?php

namespace App\Services\Admin;

use App\Models\Registration;

class RegistrationService
{
    /**
     * Update the status of a registration.
     *
     * @param int $registrationId
     * @param string $newStatus
     * @return Registration
     */
    public function updateStatus(int $registrationId, string $newStatus): Registration
    {
        $registration = Registration::findOrFail($registrationId);

        // Validate if the new status is a valid enum value
        $validStatuses = ['pending_payment', 'confirmed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status provided.");
        }

        $registration->status = $newStatus;
        $registration->save();

        // Here you could also dispatch events or send notifications, e.g.,
        // event(new RegistrationStatusUpdated($registration));

        return $registration;
    }
}
