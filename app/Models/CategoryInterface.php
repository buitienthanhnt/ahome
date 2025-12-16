<?php

namespace App\Models;

interface CategoryInterface {
    const TABLE_NAME = 'categories';

    const ATTR_PRIMARY = 'id';
    const ATTR_NAME = 'name';
    const ATTR_TYPE = 'type';
    const ATTR_DESCRIPTION = 'description';
    const ATTR_ACTIVE = 'active';
    const ATTR_PARENT_ID = 'parent_id';
    const ATTR_IMAGE_PATH = 'image_path';
    const ATTR_URL_ALIAS = 'url_alias';

    const TYPE_DEFAULT = 'default';
    const TYPE_TIME_LINE = 'time_line';

    const PRIMARY_ALIAS = 'category_id';
}
