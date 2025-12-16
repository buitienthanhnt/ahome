<?php

namespace App\Jobs;

use App\Api\PaperApi;
use App\Api\PaperFirebaseApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Thanhnt\Nan\Helper\LogTha;

// php artisan make:job UpPaperFireBase
class UpPaperFireBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paper_id;
    protected $firebaseImage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paper_id, $firebaseImage)
    {
        $this->paper_id = $paper_id;
        $this->firebaseImage = $firebaseImage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PaperFirebaseApi $paperFirebaseApi, LogTha $logTha)
    {
        $_paper = $paperFirebaseApi->getDetail($this->paper_id);
        $logTha->logFirebase('info', '<================ ' . $_paper->id . ' ================>');
        /**
         * add paper to firebase category.
         */
        $paperFirebaseApi->addPapersCategory($_paper, $this->firebaseImage);
        /**
         * up comment of paper to firebase.
         */
//        $paperFirebaseApi->upFirebaseComments($_paper); // chua cho tro

        /**
         * up tags of paper to firebase.
         */
        $paperFirebaseApi->upFirebaseTags($_paper);

        /**
         * up paper content to firestorage firebase.
         */
        $paperFirebaseApi->upContentFireStore($_paper);
        /**
         * up paper to writer in realtime database firebase
         */
        $paperFirebaseApi->upPaperWriter($_paper, $this->firebaseImage);
        $logTha->logFirebase('info', '<================ ' . $_paper->id . ' ================>');
    }
}
