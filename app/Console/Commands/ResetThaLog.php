<?php

namespace App\Console\Commands;

use App\Api\ManagerApi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResetThaLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tha:resetLog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for reset log file of tha log folder!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
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
        $this->reNameLog('event');
        $this->reNameLog('firebase');
        $this->reNameLog('error');
        $this->reNameLog('remoteSource');
        $this->reNameLog('viewCount');
        $this->reNameLog('nan_queue');
        $this->reNameLog('worker');
    }

    function reNameLog(string $file_name): bool
    {
        try {
            $now = Carbon::now()->isoFormat('D_M_Y');
            $disk = Storage::build([
                'driver' => 'local',
                'root' => storage_path(),
            ]);
            $disk->move("logs/tha/$file_name.log", "logs/tha/".$file_name.'_'.$now.".log");
            $this->info('change for new file log success!');
            return true;
        } catch (\Throwable $th) {
            $this->warn($th->getMessage());
        }
        return false;
    }
}
