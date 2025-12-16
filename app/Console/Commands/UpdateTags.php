<?php

namespace App\Console\Commands;

use App\Models\PaperTag;
use App\Models\PaperTagInterface;
use Illuminate\Console\Command;
use Thanhnt\Nan\Helper\StringHelper;

class UpdateTags extends Command
{
    use StringHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tags';

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
        $tags = PaperTag::cursor();
        foreach ($tags as $tag) {
            $base_value = strtolower($this->vn_to_str($tag->{PaperTagInterface::ATTR_VALUE}));
            $this->warn("update for: " . $tag->{PaperTagInterface::ATTR_VALUE});
            $this->info("new value: $base_value");
            $tag->{PaperTagInterface::ATTR_BASE_VALUE} = $base_value;
            $tag->save();
        }

        return 0;
    }
}
