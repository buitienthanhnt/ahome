<?php

namespace App\Models\Types;

use App\Models\Types\Base\RouteInterface;

interface CategoryInterface extends RouteInterface
{
	const TABLE_NAME = 'categories';

	const ID = 'id';
	const NAME = 'name';
	const ALIAS = 'alias';
	const ACTIVE = 'active';
	const IMAGE_PATH = 'image_path';
	const DESCIPTION = 'content';

	const PARENT = 'parent';

	const FILLED_FILEDS = [
		self::NAME,
		self::ACTIVE,
		self::ALIAS,
		self::DESCIPTION,
		self::PARENT,
	];
	const FORM_FIELDS = [
		self::NAME => ['key' => self::NAME, 'type' => FormInterface::TYPE_TEXT, 'label' => 'tên danh mục', 'required' => true],
		self::ALIAS => ['key' => self::ALIAS, 'type' => FormInterface::TYPE_TEXT, 'label' => 'đường dẫn'],
		self::ACTIVE => ['key' => self::ACTIVE, 'type' => FormInterface::TYPE_CHECKBOX, 'label' => 'trạng thái'],
		self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_FILE, 'label' => 'ảnh đại diện'],
		self::DESCIPTION => ['key' => self::DESCIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả'],
		self::PARENT => ['key' => self::PARENT, 'type' => FormInterface::TYPE_SELECT, 'label' => 'danh mục cha', 'model' => \App\Models\Category::class],
	];

	const HIDDEN_FIELDS = ['deleted_at', 'created_at', 'updated_at'];

	const PREFIX = 'category';
	const ROUTE_PREFIX = ADMIN_PREFIX . '/' . self::PREFIX;
	const CATEGORY_FILTER_KEY = 'cat';

	const MODEL_TYPE = 'category';
}
