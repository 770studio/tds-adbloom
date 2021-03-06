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
            $schedule->command('conversions:update')->hourlyAt(15)->runInBackground();

            ############ Schlesinger ############
            $schedule->command('schlesinger-surveys:update')->timezone('EST')
                ->dailyAt('02:00')->runInBackground();
            $schedule->command('schlesinger-qualifications:update')->timezone('EST')
                ->weeklyOn(1, '02:10')->runInBackground();
            $schedule->command('schlesinger-industries:update')->timezone('EST')
                ->weeklyOn(1, '02:20')->runInBackground();
            $schedule->command('schlesinger-survey-qualifications:update')->timezone('EST')
                ->weeklyOn(1, '02:30')->runInBackground();
            #TODO prune and mb move to one command or combine somehow
            ############ Schlesinger ############


            //$schedule->command('yoursurveys:update 500 CA')->everyFourHours();
            // $schedule->command('yoursurveys:update 500 US')->everyFourHours();
            // $schedule->command('dalia_publisher_api:update')->daily();
            // $schedule->command('telescope:prune --hours=240')->daily();
            //
            $schedule->command('conversions:collectHourlyStats')->hourlyAt([20, 40])->runInBackground();
            // $schedule->command('test:alert1')->hourlyAt([25, 45]);
            $schedule->command('statstests:alert2', ['--notify'])->timezone('EST')->twiceDaily(5, 15)
                ->runInBackground();
            $schedule->command('statstests:alert3', ['--notify'])->timezone('EST')->twiceDaily(5, 15)
                ->runInBackground();
            $schedule->command('conversionsHourlyStats:prune')->daily();

        }

        if (App::environment('production')) {
            $schedule->command('conversions:update')->everyThirtyMinutes()->runInBackground();
          //  $schedule->command('schlesinger-surveys:update')->hourlyAt(20)->runInBackground();

            // $schedule->command('yoursurveys:update 1000 CA')->hourly();
            // $schedule->command('yoursurveys:update 1000 US')->hourly();
            // $schedule->command('dalia_publisher_api:update')->daily();

        }


        $schedule->command('partner:send_pb2')->everyTwoMinutes()->runInBackground();
        $schedule->command('horizon:snapshot')->everyFiveMinutes()->runInBackground();

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
