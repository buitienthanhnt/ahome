<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Thanhnt\Nan\Helper\LogTha;
use Thanhnt\Nan\Helper\DomHtml;
use Thanhnt\Nan\Helper\RemoteSourceManager;
use App\Models\Paper;

class RemoteRource extends Command
{
    use DomHtml;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remoteSource:update';

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
        RemoteSourceManager $remoteSourceManager,
        Paper $paper
    )
    {
        $this->remoteSourceManager = $remoteSourceManager;
        $this->paper = $paper;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sourcePath = storage_path(LogTha::LOG_PATH.LogTha::SOURCE_URL_TYPE.'.log');
        // file(path) return array of line.
        $logLines = file($sourcePath);
        foreach ($logLines as $key => $value) {
            $sourceUri = trim(\explode('LogEvent:', $value, 2)[1]);
            if(!$this->remoteSourceManager->checkSourceExit($sourceUri)){
                try {
                    $data = $this->remoteSourceManager->source($sourceUri);
                    $paper = new Paper();
                    $paper->fill($data)->save();
                    $this->remoteSourceManager->save_remote_source_history($sourceUri, 1, $paper->id, true);
                    echo("added remoteSource to database with uri: $sourceUri \n");
                } catch (\Throwable $th) {
                    echo("add source: $sourceUri error: ". $th->getMessage());
                }
               
            }else {
                echo("source uri: $sourceUri exist \n");
            }
        }
    }
}
