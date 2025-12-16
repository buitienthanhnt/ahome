<?php

namespace App\Models\Types;

use App\Models\Types\Base\TimestampInterface;

interface PageInterface extends TimestampInterface
{
    const TABLE_NAME = 'pages';

    const ID = 'id';
    const TITLE = 'title';
    const ALIAS = 'alias';
    const IMAGE_PATH = 'image_path';
    const DESCRIPTION = 'desciption';
    const CATEGORY = 'category';
    const ABOVE = 'above';

    const WRITER = 'writer';
    const TAGS = 'tags';

    /**
     * khai báo thuộc tính biểu mẫu để tạo form.
     * define: [string $key => ['key' => string, 'type' => string, 'label ?=> string]][]
     */
    const FORM_FIELDS = [
        self::TITLE => ['key' => self::TITLE, 'type' => FormInterface::TYPE_TEXT, 'label' => 'tiêu đề', 'required' => true],
        self::ACTIVE => ['key' => self::ACTIVE, 'type' => FormInterface::TYPE_CHECKBOX],
        self::ABOVE => ['key' => self::ABOVE, 'type' => FormInterface::TYPE_CHECKBOX, 'value' => false],
        self::ALIAS => ['key' => self::ALIAS, 'type' => FormInterface::TYPE_TEXT, 'label' => 'đường dẫn'],
        self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'ảnh đại diện'],
        self::DESCRIPTION => ['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả'],
        self::CATEGORY => ['key' => self::CATEGORY, 'type' => FormInterface::TYPE_MULTISELECT, 'label' => 'danh mục', 'model' => \App\Models\Page::class],
        // self::CONTENT => ['key' => self::CONTENT, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'noi dung'],
        self::WRITER => ['key' => self::WRITER, 'type' => FormInterface::TYPE_SELECT, 'label' => 'tác giả', 'model' => \App\Models\Page::class, 'required' => true],
        self::TAGS => ['key' => self::TAGS, 'type' => FormInterface::TYPE_MULTISELECT, 'label' => 'liên kết', 'required' => false],
    ];

    /**
     * khai báo danh sách các thuộc tính được gán hàng loạt.
     */
    const FILLED_FILEDS = [self::TITLE, self::ACTIVE, self::ABOVE, self::ALIAS, self::IMAGE_PATH, self::DESCRIPTION, self::WRITER];

    const PREFIX = 'page';
    const ROUTE_PREFIX = ADMIN_PREFIX . '/' . self::PREFIX;

    const ROUTE_ACTION = [
        'list' => '',
        'create' => 'create',
        'register' => 'register',
        'detail' => 'detail/{id}',
        'delete' => 'delete/{id}',
        'edit' => 'edit/{id}',
        'update' => 'update/{id}',
    ];

    const MODEL_TYPE = 'page';
}
