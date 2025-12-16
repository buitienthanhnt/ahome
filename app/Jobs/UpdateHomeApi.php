<?php

namespace App\Jobs;

use App\Api\ManagerApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

// implements: ShouldBeUnique sẽ chỉ thực hiện 1 hành động kể cả khi có nhiều job trong hàng đợi
class UpdateHomeApi implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct(
    //     Request $request,
    //     ManagerApi $managerApi
    // ) {
    //     $this->request = $request;
    //     $this->managerApi = $managerApi;
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request, ManagerApi $managerApi)
    {
        /**
         * cache for homeInfo api
         */
        $request->headers->set("forget_cache", true);
        $request->merge([
            "TopNew" => 1,
            "Popular" => 1,
            "TopSearch" => 1,
            "Forward" => 1,
            "TimeLine" => 1,
            "Pro" => 1,
            "Video" => 1,
            "Images" => 1,
            "Chart" => 1,
            "ListWriter" => 1,
            "Random" => 1,
            "SearchAll" => 1,
            "Default" => 1
        ]);
        $managerApi->getHomeInfo();
        Log::alert('update home api cache after insert new paper with: forget_cache!');
    }
}
