<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $message)
    {
        $this->email = $email;      // get param in dispath function
        $this->message = $message;  // get param in dispath function
    }

    /**
     * Execute the job.
     * Run: php artisan queue:work
     *
     * @return void
     */
    public function handle()
    {
        $channel = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/tha/nan_queue.log'),
        ]);
        Log::stack([$channel])->info('demo queue in custom nan_queue log file!:  '. $this->email);

        try {
            // Mail::to('buisuphu01655@gmail.com')->send(new UserEmail());  // php artisan make:mail UserEmail

            Mail::send('welcome', [], function ($message) {
                $message->from('buitienthanhnt@gmail.com', "tha nan");
                $message->to("thanh.bui@jmango360.com", 'user1');
                $message->subject("demo by send mail laravel newpaper");
            }
            );
        } catch (\Throwable $th) {
            //throw $th;
            // echo ($th->getMessage());
            Log::stack([$channel])->info($th->getMessage());
            return;
        }

    }
}
