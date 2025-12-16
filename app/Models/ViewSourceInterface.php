<?php

namespace App\Models;

interface ViewSourceInterface
{
    const TABLE_NAME = 'view_sources';

    const ATTR_SOURCE_ID = 'source_id';
    const ATTR_TYPE = 'type';
    const ATTR_VALUE = 'value';
    const ATTR_LIKE = 'like';
    const ATTR_HEART = 'heart';

    const TYPE_PAPER = "paper";
    const TYPE_CATEGORY = "category";

    const PARAM_TYPE = 'type';

    const PARAM_ACTION = 'action';
    const ACTION_VAL_ADD = 'add';
    const ACTION_VAL_SUB = 'sub';
}
