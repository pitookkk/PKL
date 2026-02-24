<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MaintenanceService;

class SendMaintenanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-maintenance-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for assets needing maintenance and sends reminders to users.';

    /**
     * Execute the console command.
     */
    public function handle(MaintenanceService $maintenanceService)
    {
        $this->info('Checking for maintenance schedules and sending reminders...');
        
        $maintenanceService->sendMaintenanceReminders();
        
        $this->info('Done.');
    }
}
