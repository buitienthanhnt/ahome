<?php

namespace App\Models\Types;

use App\Models\Types\Base\RouteInterface;

interface WriterInterface extends RouteInterface
{
	/**
	 * khai báo tên bảng trong database.
	 */
	const TABLE_NAME = 'writers';

	/**
	 * khai báo các thuộc tính đối tượng.
	 */
	const ID = 'id';
	const NAME = 'name';
	const EMAIL = 'email';
	const ALIAS = 'alias';
	const PHONE = 'phone';
	const ADDRESS = 'address';
	const IMAGE_PATH = 'image_path';
	const DESCRIPTION = 'description';
	const DATE_OF_BIRTH = 'date_of_birth';

	const _PAGES = 'pages';

	/**
	 * khai báo thuộc tính biểu mẫu để tạo 1 tác giả.
	 */
	const FORM_FIELDS = [
		self::NAME => ['key' => self::NAME, 'type' => FormInterface::TYPE_TEXT, 'label' => 'tên tác giả'],
		self::EMAIL => ['key' => self::EMAIL, 'type' => FormInterface::TYPE_EMAIL],
		self::ACTIVE => ['key' => self::ACTIVE, 'type' => FormInterface::TYPE_CHECKBOX],
		self::ALIAS => ['key' => self::ALIAS, 'type' => FormInterface::TYPE_TEXT, 'label' => 'bút danh'],
		self::PHONE => ['key' => self::PHONE, 'type' => FormInterface::TYPE_PHONE, 'label' => 'sdt'],
		self::ADDRESS => ['key' => self::ADDRESS, 'type' => FormInterface::TYPE_TEXT, 'label' => 'địa chỉ'],
		self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_FILE, 'label' => 'ảnh đại diện'],
		self::DESCRIPTION => ['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'ghi chú'],
		self::DATE_OF_BIRTH => ['key' => self::DATE_OF_BIRTH, 'type' => FormInterface::TYPE_DATE, 'label' => 'ngày sinh']
	];

	/**
	 * khai báo danh sách các thuộc tính được gán hàng loạt.
	 */
	const FILLED_FILEDS = [self::NAME, self::EMAIL, self::ACTIVE, self::ALIAS, self::ADDRESS, self::PHONE, self::DESCRIPTION, self::DATE_OF_BIRTH];

	const HIDDEN_FIELDS = [self::ACTIVE, 'deleted_at', 'created_at', 'updated_at'];

	const PREFIX = 'writer';
	const ROUTE_PREFIX = ADMIN_PREFIX . '/' . self::PREFIX;

	const WRITER_FILTER_KEY = 'writer';
}
