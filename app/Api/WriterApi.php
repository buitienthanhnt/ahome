<?php

namespace App\Api;

use App\Api\Convert\ConvertPaper;
use App\Api\Convert\ConvertWriter;
use App\Enums\CacheStorage;
use App\Helper\HelperFunction;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Thanhnt\Nan\Helper\CacheManager;

final class WriterApi extends BaseApi
{
    protected $writer;
    protected $helperFunction;
    protected $request;

    protected $writerRepository;

    protected $convertWriter;
    protected $convertPaper;
    protected $cacheManager;

    function __construct(
        HelperFunction $helperFunction,
        Writer $writer,
        Request $request,
        WriterRepository $writerRepository,
        ConvertPaper $convertPaper,
        ConvertWriter $convertWriter,
        CacheManager $cacheManager
    )
    {
        $this->writer = $writer;
        $this->helperFunction = $helperFunction;
        $this->request = $request;
        $this->writerRepository = $writerRepository;
        $this->convertPaper = $convertPaper;
        $this->convertWriter = $convertWriter;
        $this->cacheManager = $cacheManager;
    }

    function listWriter()
    {
        $writerList = $this->writerRepository->listWriter();
        return $this->convertWriter->convertPaginate($writerList);
    }

    function getPapers($writer_id)
    {
        $limit = $this->request->get("limit", 12);
        $page = $this->request->get("page", 1);
        /**
         * @var Writer $writer
         */
        $writer = $this->writer->find($writer_id);
        if (empty($writer)){
            return null;
        }
        return $this->cacheManager->getAndCache(
            CacheStorage::WRITER_PAPER . $writer_id . "_" . $limit . "_" . $page,
            function () use ($writer) {
                return $this->convertPaper->convertPaperPaginate($writer->getPaperWithPaginate());
            },
            CacheStorage::TIME_4_H
        );
    }

    function allWriter(int $max = null)
    {
        $listCache = "listWriters_" . ($max ?: 'full');
        if (Cache::has($listCache)) {
            return Cache::get($listCache);
        }
        $listWriters = $this->convertWriter->convertListData($this->writerRepository->allWriter($max));
        Cache::add($listCache, $listWriters, 60 * 60 * 24);
        return $listWriters;
    }

    function addPaperComment(int $paper_id)
    {

    }
}
