<?php

namespace App\Observers;

use App\Models\Paper;

class PaperObserver
{
    /**
     * Handle the Paper "created" event.
     *
     * @param  \App\Models\Paper  $paper
     * @return void
     */
    public function created(Paper $paper)
    {
        //
    }

    /**
     * Handle the Paper "updated" event.
     *
     * @param  \App\Models\Paper  $paper
     * @return void
     */
    public function updated(Paper $paper)
    {
        //
    }

    /**
     * Handle the Paper "deleted" event.
     *
     * @param  \App\Models\Paper  $paper
     * @return void
     */
    public function deleted(Paper $paper)
    {
        //
    }

    /**
     * Handle the Paper "restored" event.
     *
     * @param  \App\Models\Paper  $paper
     * @return void
     */
    public function restored(Paper $paper)
    {
        //
    }

    /**
     * Handle the Paper "force deleted" event.
     *
     * @param  \App\Models\Paper  $paper
     * @return void
     */
    public function forceDeleted(Paper $paper)
    {
        //
    }
}
