<?php
// php artisan make:command IndexCommand
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class IndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paper:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index for paper lists';

    /**
     * Create a new command instance.
     *
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * https://viblo.asia/p/tim-hieu-ve-task-scheduling-trong-laravel-aWj53O6w56m
     *
     * @return int
     */
    public function handle()
    {
        Log::info('tha write log by command scheduling');
        return 0;
    }
}
