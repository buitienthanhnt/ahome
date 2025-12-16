<?php
namespace App\Helper;

use App\Models\Paper;
use App\Models\PaperInterface;

class RouterHelper{
    function __construct(){
    }

    function paperDetailUrl(Paper $paper): string
    {
        return route('front_paper_detail', ['paper_id' => $paper->id, 'alias' => $paper->{PaperInterface::ATTR_URL_ALIAS}]);
    }
}
