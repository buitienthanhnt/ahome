<?php

namespace App\Models;

interface WriterInterface{
    const TABLE_NAME = 'writers';

    const ATTR_NAME = 'name';
    const ATTR_EMAIL = 'email';
    const ATTR_PHONE = 'phone';
    const ATTR_ADDRESS = 'address';
    const ATTR_GROUP = 'group';
    const ATTR_IMAGE_PATH = 'image_path';
    const ATTR_NAME_ALIAS = 'name_alias';
    const ATTR_ACTIVE = 'active';
    const ATTR_RATING = 'rating';
    const ATTR_DATE_OF_BIRTH = 'date_of_birth';
}
?>