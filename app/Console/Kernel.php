<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void

     * * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
     OR
     * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('post:cron')->everyMinute();
        $schedule->command('reminder:cron')->everyMinute();
        
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
