<?php

namespace App\Api;

use App\Api\Data\Writer\WriterItem;
use App\Enums\CacheStorage;
use App\Models\Writer;
use Illuminate\Support\Facades\Cache;

class WriterRepository
{
    protected $writer;
    protected $pageInfo;

    function __construct(
        Writer $writer
    ) {
        $this->writer = $writer;
    }

    /**
     * @param int $writer_id
     * @return WriterItem
     */
    function getById(int $writer_id)
    {
        $cacheKey = CacheStorage::WRITER_DETAIL_MODEL.$writer_id;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $writer = Writer::find($writer_id);
        Cache::put($cacheKey, $writer);
        return $writer;
    }

    /**
     * @return WriterItem[]
     */
    function listWriter()
    {
        return $this->writer->all()->toQuery()->paginate(12);
    }

    /**
     * @return WriterItem[]|null
     */
    public function allWriter(int $max = null)
    {
        $all_writers = $this->writer->all();
        if ($max) {
            $all_writers->toQuery()->limit($max);
        }
        return $all_writers;
    }
}
