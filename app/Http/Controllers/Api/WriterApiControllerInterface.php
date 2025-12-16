<?php
namespace App\Http\Controllers\Api;

interface WriterApiControllerInterface{
    const CONTROLLER_NAME = '\App\Http\Controllers\Api\WriterApiController';

    const PAPER_BY_WRITER = 'getPaperByWriter';
    const WRITER_LIST = 'getWriterList';

    /**
     * @param int $writer_id
     */
    public function getPaperByWriter(int $writer_id);

    public function getWriterList();
}