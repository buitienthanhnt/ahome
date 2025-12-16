<?php

namespace App\Console\Commands;

use App\Models\Paper;
use App\Models\PaperContent;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\LogTha;

class ChangeSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tha:changeSource {paper_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'coppy source from root paper(param = [int|all])';

    /**
     * @var Paper $paper
     */
    protected $paper;

    /**
     * @var PaperContent $paperContent
     */
    protected $paperContent;

    /**
     * @var LogTha $logTha
     */
    protected $logTha;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        Paper $paper,
        PaperContent $paperContent,
        LogTha $logTha
    ) {
        $this->paper = $paper;
        $this->paperContent = $paperContent;
        $this->logTha = $logTha;
        parent::__construct();
    }

    protected function checkExist(int $paper_id): bool
    {
        $paperConten = PaperContent::where('paper_id', '=', $paper_id)->select()->first();
        if ($paperConten && $paperConten->id) {
            return true;
        }
        return false;
    }

    /**
     * @param Paper $paper
     * @return void
     */
    function moveProduct($paper): void
    {
        if ($paper->type === 'product') {
            $val = DB::table('price')->where('paper_id', $paper->id)->get()->first();
            $this->paperContent->create([
                "type" => PaperContent::TYPE_PRICE,
                "key" => PaperContent::TYPE_PRICE,
                "value" => $val->value,
                "paper_id" => $paper->id,
                "depend_value" => null,
            ]);
        }
    }

    /**
     * @param Paper $paper
     * @return void
     */
    function moveSlider($paper): void
    {
        return;
        if ($paper->type === 'carousel') {
            $slider = $paper->sliderImages();
            if ($slider->first()) {
                $this->paperContent->create([
                    "type" => PaperContent::TYPE_SLIDER,
                    "key" => PaperContent::TYPE_SLIDER,
                    "value" => $slider->value,
                    "paper_id" => $paper->id,
                    "depend_value" => null,
                ]);
            }
        }
    }

    protected function moveData($paper_id): void
    {
        try {
            if ($this->checkExist($paper_id)) {
                $this->error("conten of the paper_id=$paper_id  has exist!");
                return;
            }
            /**
             * @var Paper $paper
             */
            $paper = $this->paper->find($paper_id);
            if (!$paper) {
                throw new Exception("source of the paper_id not found!");
            }
            /**
             * move for content
             */
            if ($paper->conten) {
                $this->paperContent->create([
                    "type" => "conten",
                    "key" => "conten",
                    "value" => $paper->conten,
                    "paper_id" => $paper_id,
                    "depend_value" => null,
                ]);
            }
            /**
             * move product attribute
             */
            $this->moveProduct($paper);
            /**
             * move slider carousel
             */
            $this->moveSlider($paper);

            $this->logTha->logEvent('info', "moved for paper of: $paper_id ($paper->title)");
            $this->info("moved for paper of: $paper_id \n");
        } catch (\Throwable $th) {
            $this->error('-----> ' . $th->getMessage());
            //throw $th;
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $move_paper = $this->argument('paper_id');
        if ($move_paper === 'all') {
            $exist_ids = array_column(PaperContent::all(['paper_id'])->unique('paper_id')->toArray(), 'paper_id');
            $paper_ids = array_column(Paper::all(['id'])->whereNotIn('id', $exist_ids)->toArray(), 'id');
            if (empty($paper_ids)) {
                $this->info('not has new source for update!');
                return;
            }
            for ($i = 0; $i < count($paper_ids); $i++) {
                $this->moveData($paper_ids[$i]);
            }
        } else {
            $this->moveData($move_paper);
        }
    }
}
