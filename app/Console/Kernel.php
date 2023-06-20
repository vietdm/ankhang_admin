<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('value-of-akg:update')->cron('0 0 */3 * *');
        $schedule->command('user:calc-bonus-monthly')->dailyAt('00:00');
        $schedule->command('app:auto-update-total-akg')->dailyAt('00:00');
        $schedule->command('app:refactor-user-deleted')->dailyAt('00:10');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
