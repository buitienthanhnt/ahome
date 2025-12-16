<?php

namespace App\Console\Commands;

use App\Models\Paper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Laravelium\Sitemap\Sitemap;

class CreateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        /**
         * @var Sitemap $sitemap
         */
        $sitemap = App::make('sitemap');
        // add home pages mặc định
        $sitemap->add(route('/'), Carbon::now(), '1.0', 'daily');

        // add bài viết
        /**
         * @var Paper[] $papers
         */
        Paper::chunkById(200, function (Collection $papers) use($sitemap){
            $papers->map(function ($paper)use($sitemap){
                $sitemap->add($paper->getUrl(), $paper->created_at, '0.6', 'daily');
            });
            }, column: 'id');

        // lưu file và phân quyền
        $sitemap->store('xml', 'sitemap');
        if (Storage::exists(public_path() . '/sitemap.xml')) {
            chmod(public_path() . '/sitemap.xml', 0777);
        }
        return 0;
    }
}
