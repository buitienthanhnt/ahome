<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * https://viblo.asia/p/tim-hieu-ve-task-scheduling-trong-laravel-aWj53O6w56m
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\IndexCommand', // add by tha
        'Thanhnt\Nan\Commands\DemoCommand',  // add for class in custom pack
        'App\Console\Commands\PaperFirebase', // add by tha for sync paper data from firebase
        'App\Console\Commands\UpdateImagePath', // add by tha for update image path with
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // chạy: php artisan schedule:run để chạy thủ công
        // hoặc:
        // crontab -e để cài đặt
        // mở: * * * * * php /var/www/amua/artisan schedule:run
        // nên dùng: * * * * * php /var/www/html/laravel1/artisan schedule:run 1>> /dev/null 2>&1
        // để chạy tự động trên ubuntu 1 phút 1 lần .
        // */5 * * * * /path/to/script // 5 phút ubuntu chạy 1 lần
        // xem:
        // https://www.digitalocean.com/community/tutorials/how-to-use-cron-to-automate-tasks-ubuntu-1804
        // https://askubuntu.com/questions/1138195/what-does-21-means-in-crontab-commands
        // https://tldp.org/LDP/abs/html/io-redirection.html

        $schedule->command('tha:resetLog')->weekends();
        // $schedule->command('tha:changeSource 20')->everyFiveMinutes();
        // $schedule->command('tha:changeSource 20')->dailyAt('04:26');
        // $schedule->command('homeInfo:cache')->hourlyAt(['0', '30']); // mooxi 22 phust

        // $schedule->command('inspire')->hourly();
        // $schedule->command('paper:index')->dailyAt('16:05'); // cron job trong laravel: add by tha
        // $schedule->command('paperFirebase:sync')->everySixHours(); // cron job trong laravel: add by tha
        // $schedule->command('backup:run')->daily();  // backup data
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
