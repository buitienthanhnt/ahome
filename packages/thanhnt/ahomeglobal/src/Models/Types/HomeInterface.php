<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface HomeInterface
{
    const TABLE_NAME = 'homes';

    const ID = 'id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const DISTRICT = 'district';
    const IMAGE_PATH = 'image_path';
    /**
     * khai báo thuộc tính biểu mẫu để tạo form.
     * define: [string $key => ['key' => string, 'type' => string, 'label ?=> string]][]
     */
    const ATTR_G_MAP = 'g_map';
    const ATTR_LOCATION = 'location';
    const ATTR_RATE = 'rate';

    const FORM_FIELDS = [
        self::NAME => ['key' => self::NAME, 'type' => FormInterface::TYPE_TEXT, 'label' => 'tên khách sạn', 'required' => true,],
        self::DESCRIPTION => ['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả chung',],
        self::DISTRICT => ['key' => self::DISTRICT, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'địa chỉ',],
        self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'image avatar',],
    ];

    const CUSTOM_ATTRS = [
        self::ATTR_G_MAP => ['key' => self::ATTR_G_MAP, 'type' => FormInterface::TYPE_TEXT, 'label' => 'địa chỉ google(lat-lng)','placeholder' => 'ex: 20.98245366081677, 105.81097140400442', 'show_filter' => false],
        self::ATTR_LOCATION => ['key' => self::ATTR_LOCATION, 'type' => FormInterface::TYPE_TEXT, 'label' => 'bản đồ', 'placeholder' => 'bản đồ'],
        self::ATTR_RATE => ['key' => self::ATTR_RATE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'đánh giá', 'placeholder' => 'đánh giá'],
    ];

    /**
     * khai báo danh sách các thuộc tính được gán hàng loạt.
     */
    const FILLED_FILEDS = [self::NAME, self::DESCRIPTION, self::DISTRICT, self::IMAGE_PATH,];

    /**
     * define hidden fields
     */
    const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at',];

    const PREFIX = 'home';
    const ROUTE_PREFIX = ADMIN_PREFIX . '/ahome';
}
