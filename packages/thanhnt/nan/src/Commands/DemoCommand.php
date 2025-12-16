<?php

namespace Thanhnt\Nan\Commands;

use Illuminate\Console\Command;

class DemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     * add: 'Thanhnt\Nan\Commands\Demo' to protected $commands in: App\Console\Kernel
     *
     * @var string
     */
    protected $signature = 'tha:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update remote source';

    protected $remoteSourceManager;
    protected $paper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
    )
    {
        

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo(123123);
    }
}
