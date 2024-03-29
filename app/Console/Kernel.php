<?php

namespace App\Console;

use App\Console\Commands\ScreenshotDelete;
use App\Console\Commands\TruncateTokens;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    protected $commands = [
        ScreenshotDelete::class,
        TruncateTokens::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('screenshot_delete')->everyTwoHours()->runInBackground();
        $schedule->command('truncate_tokens')->dailyAt('07:00')->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
