<?php

namespace App\Api\Convert;

use App\Api\Data\Paper\Tag;
use App\Models\PaperTagInterface;


class ConvertTag extends ConvertPagination
{

    public function convertItemData($item)
    {
        // TODO: Implement convertItemData() method.
        $_tag = new Tag();
        $_tag->setId($item->id);
//        $_tag->setEntityId($item->{PaperTagInterface::ATTR_ENTITY_ID});
        $_tag->setValue($item->{PaperTagInterface::ATTR_VALUE});
        $_tag->setType($item->{PaperTagInterface::ATTR_TYPE});
        $_tag->setBaseValue($item->{PaperTagInterface::ATTR_BASE_VALUE} ?: $item->{PaperTagInterface::ATTR_VALUE});
        return $_tag;
    }
}
