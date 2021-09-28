<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

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
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // no work on prod for now
        /*  $schedule->command('conversions:update')
              ->environments(['production'])
              ->hourly();*/

        if (App::environment('local')) {
            return;
        }

        if (App::environment('staging')) {
            $schedule->command('conversions:update')->hourlyAt(15);
            $schedule->command('yoursurveys:update 500 CA')->everyFourHours();
            $schedule->command('yoursurveys:update 500 US')->everyFourHours();
            $schedule->command('dalia_publisher_api:update')->daily();
           // $schedule->command('telescope:prune --hours=240')->daily();
        }

        if (App::environment('production')) {
            $schedule->command('conversions:update')->everyThirtyMinutes();
            $schedule->command('yoursurveys:update 1000 CA')->hourly();
            $schedule->command('yoursurveys:update 1000 US')->hourly();
            $schedule->command('dalia_publisher_api:update')->daily();

        }


        $schedule->command('partner:send_pb2')->everyTwoMinutes();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

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
