<?php

namespace App\Console\Commands;

use App\Models\Paper;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class UpdateImagePath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update for new image path';

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
        $storage_path = getStoragePath();
        $this->warn($storage_path);
        Paper::chunkById(10, function(Collection $papers)use($storage_path){
            foreach ($papers as $paper) {
                if (strpos($paper->image_path, '/storage/')) {
//                    return $this->replaceImageUrl(url(Storage::url(explode("/storage/", $paper->image_path)[1])));
                    $paper->update(["image_path" => explode("/storage/", $paper->image_path, 2)[1]]);
                }

//                if (str_starts_with($paper->image_path, $storage_path)) {
//                    // $paper->update(["image_path" => ltrim($paper->image_path, '/')]);
//
//                }
            }
        }, $column = 'id');

        // $papers = Paper::cursor();
        // $storage_path = url(Storage::url(''));
        // foreach ($papers as $paper) {
        //     $this->warn($paper->image_path);
        //     if (str_starts_with($storage_path, $paper->image_path)) {
        //         $this->info(str_replace($storage_path, '', $paper->image_path));
        //         dd();
        //         $paper->image_path = str_replace($storage_path, '', $paper->image_path);
        //         $paper->save();
        //     }
        // }
        $this->info(123);
        return 0;
    }
}
