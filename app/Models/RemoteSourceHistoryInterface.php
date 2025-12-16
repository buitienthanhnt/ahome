<?php

namespace App\Models;

interface RemoteSourceHistoryInterface
{
    const TABLE_NAME = 'remote_source_histories';

    const ATTR_URL_VALUE = 'url_value';
    const ATTR_TYPE = 'type';
    const ATTR_ACTIVE = 'active';
    const ATTR_PAPER_ID = PaperInterface::PRIMARY_ALIAS;

    const TYPE_PAPER = 'paper';
    const TYPE = 'category';
}
