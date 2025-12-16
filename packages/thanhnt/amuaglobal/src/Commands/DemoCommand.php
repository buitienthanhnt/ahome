<?php

namespace Thanhnt\Amuaglobal\Commands;

use Illuminate\Console\Command;

class DemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     * add: 'Thanhnt\Nan\Commands\Demo' to: AmuaglobalProvider
     *
     * @var string
     */
    protected $signature = 'amua:test';

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
        $this->info('the message is rended by package: thanhnt/amuaglobal');
		$this->info('the class: '.__CLASS__);
		$this->warn('the line: '.__LINE__);
		$this->warn('the file: '.__FILE__);
		$this->error(__FUNCTION__);
		$this->error(__METHOD__);
    }
}
