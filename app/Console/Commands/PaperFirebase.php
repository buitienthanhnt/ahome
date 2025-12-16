<?php

namespace App\Console\Commands;

use App\Api\PaperApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PaperFirebase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paperFirebase:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync paper data from firebase';

    protected $paperApi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        PaperApi $paperApi
    )
    {
        $this->paperApi = $paperApi;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->paperApi->pullFirebaseComLike();
        // $this->paperApi->pullFirebaseComment();
        // $this->paperApi->pullFirebasePaperLike();
        // Log::info('sync data from firebase');
        // return 0;
    }
}
