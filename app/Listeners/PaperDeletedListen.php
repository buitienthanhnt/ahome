<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PaperDeletedListen
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(\App\Events\PaperDeleted $event)
    {
        $paper = $event->paper;

        /**
         * delete paper content.
         */
        $paper->joinContent()->delete();

        /**
         * remove paper tags
         */
        $paper->joinTags()->delete();

        /**
         * delete paper categories.
         */
        $paper->toPaperCategory()->delete();
    }
}
