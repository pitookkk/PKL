<?php

namespace App\Services;

use App\Models\MaintenanceSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Services\WhatsAppService; // Import the WhatsAppService

class MaintenanceService
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Check for upcoming maintenance and send reminders.
     *
     * @return void
     */
    public function sendMaintenanceReminders(): void
    {
        $today = Carbon::today();

        $schedules = MaintenanceSchedule::with('customerAsset.user')
            ->where('next_service_reminder', $today)
            ->get();

        foreach ($schedules as $schedule) {
            $user = $schedule->customerAsset->user;
            $asset = $schedule->customerAsset;

            // Send Email Notification
            // Mail::raw("Hi {$user->name}, this is a reminder that your {$asset->device_name} is due for maintenance.", function ($message) use ($user) {
            //     $message->to($user->email)
            //             ->subject('Upcoming Maintenance Reminder');
            // });

            // Send WhatsApp Notification (using the new WhatsAppService)
            if ($user->phone_number) { // Assuming User model has a 'phone_number' field
                $message = "Halo {$user->name}, ini adalah pengingat bahwa {$asset->device_name} Anda akan segera diservis di Pitocom. Silakan jadwalkan atau hubungi kami.";
                $this->whatsAppService->sendMessage($user->phone_number, $message);
                logger()->info("WhatsApp reminder sent to {$user->phone_number} for asset: {$asset->device_name}");
            } else {
                logger()->warning("User {$user->id} does not have a phone number for WhatsApp reminder.");
            }
        }
    }
}
