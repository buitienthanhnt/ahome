<?php

namespace App\Models;

interface PaperTagInterface {
    const TABLE_NAME = 'paper_tag';

    const ATTR_VALUE = 'value';
    const ATTR_ENTITY_ID = 'entity_id';
    const ATTR_TYPE = 'type';
    const ATTR_BASE_VALUE = 'base_value';

    const TYPE_PAPER = 'paper_tag';
    const TYPE_CATEGORY = 'category_tag';
}
