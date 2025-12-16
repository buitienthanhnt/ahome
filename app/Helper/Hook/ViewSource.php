<?php
namespace App\Helper\Hook;

use Exception;
use Illuminate\Support\Facades\DB;

trait ViewSource{

    public function addViewCount($type = 'paper', $source_id) {
        if ($type && $source_id) {
            DB::beginTransaction();
            try {

            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }
    }
}


?>
