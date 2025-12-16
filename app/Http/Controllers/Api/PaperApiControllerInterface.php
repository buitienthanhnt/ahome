<?php

namespace App\Http\Controllers\Api;

interface PaperApiControllerInterface
{
    const CONTROLLER_NAME = '\App\Http\Controllers\Api\PaperApiController';

    const API_PAPER_ADD_LIKE = 'addPaperLike';
    const LIST_PAPERS = 'listPapers';
    const PAPER_DETAIL = 'getPaperDetail';
    const PAPER_RELATED = 'getRelatedPaper';
    const PAPER_RANDOM = 'getRandomPaper';
    const PAPER_BY_ID = 'getPaperByIds';

    function addPaperLike(int $paper_id);

    /**
     * @param int $paper_id
     * @return mixed
     */
    public function getPaperDetail(int $paper_id);

    /**
     * get paper list for all
     */
    public function listPapers();

    /**
     * @param int $paper_id
     */
    public function getRelatedPaper(int $paper_id);

    /**
     * get paper random
     */
    public function getRandomPaper();

    /**
     * get paper random
     */
    public function getPaperByIds();
}
