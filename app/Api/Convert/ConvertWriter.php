<?php

namespace App\Api\Convert;

use App\Api\Data\Writer\WriterItem;
use App\Api\Data\Writer\WriterList;
use App\Models\Writer;
use App\Models\WriterInterface;

class ConvertWriter extends ConvertPagination
{
    protected $writer;
    protected $writerList;
    protected $pageInfo;

    function __construct(
        Writer $writer,
        WriterList $writerList
    ) {
        $this->writer = $writer;
        $this->writerList = $writerList;
    }

    /**
     * @param Writer $writer
     * @return WriterItem
     */
    function convertItemData($item): WriterItem
    {
        $writerItem = new WriterItem();
        $writerItem->setId($item->id);
        $writerItem->setName($item->{WriterInterface::ATTR_NAME});
        $writerItem->setEmail($item->{WriterInterface::ATTR_EMAIL});
        $writerItem->setPhone($item->{WriterInterface::ATTR_PHONE});
        $writerItem->setImagePath($item->getImagePath());
        $writerItem->setRating($item->{WriterInterface::ATTR_RATING});
        $writerItem->setActive($item->{WriterInterface::ATTR_ACTIVE});
        $writerItem->setDateOfBirth($item->{WriterInterface::ATTR_DATE_OF_BIRTH});
        return $writerItem;
    }

    /**
     * @param $paginateDatas
     * @return WriterList
     */
    function convertPaginate($paginateDatas)
    {
        $writerList = $this->writerList;
        $writerList->setItems($this->convertListData($paginateDatas));
        $writerList->setPageInfo($this->convertPageInfo($paginateDatas));
        return $writerList;
    }
}
