<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface RoomInterface
{
	const TABLE_NAME = 'rooms';

	const ID = 'id';
	const TITLE = 'title';
	const DESCRIPTION = 'description';
	/**
	 * one, two, three, four, five, six
	 */
	const TYPE = 'type';
	const IMAGE_PATH = 'image_path';
	const TYPE_VALUE = [
		['label' => '1 giường đơn', 'value' => '1',],
		['label' => '1 giường đôi', 'value' => '2',],
		['label' => '1 giường đôi + 1 đơn', 'value' => '2+1',],
		['label' => '2 giường đôi', 'value' => '4',],
		['label' => '2 giường đôi + 1 đơn', 'value' => '5',],
	]; //['one', 'two', 'three', 'four', 'five', 'six', 'all']; // depend of [type]
	const HOME_ID = 'home_id';

	/**
	 * khai báo thuộc tính biểu mẫu để tạo form.
	 * define: [string $key => ['key' => string, 'type' => string, 'label ?=> string]][]
	 */
	const ATTR_PRICE = 'price';
	const ATTR_BED = 'bed';
	const ATTR_PERSON = 'persion';
	const ATTR_RATE = 'rate';

	const FORM_FIELDS = [
		self::TITLE => ['key' => self::TITLE, 'type' => FormInterface::TYPE_TEXT, 'label' => 'tên phòng', 'required' => true],
		self::DESCRIPTION => ['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả chung',], // ex: 1 ngủ, có điều hòa, tầng 2
		self::TYPE => ['key' => self::TYPE, 'type' => FormInterface::TYPE_SELECT, 'label' => 'số giường', 'model' => \Thanhnt\Ahomeglobal\Models\Room::class, 'required' => true,],
		self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'image avatar',],
	];

	const CUSTOM_ATTRS = [
		self::ATTR_PRICE => ['key' => self::ATTR_PRICE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'Giá phòng(1 ngày)', 'required' => true, 'placeholder' => 'Giá'],
		// self::ATTR_BED => ['key' => self::ATTR_BED, 'type' => FormInterface::TYPE_SELECT, 'label' => 'số giường', 'model' => \Thanhnt\AhomeGlobal\Models\Room::class, 'required' => true,],
		self::ATTR_PERSON => ['key' => self::ATTR_PERSON, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'số lượng người', 'placeholder' => ''],
		self::ATTR_RATE => ['key' => self::ATTR_RATE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'đánh giá', 'placeholder' => ''],
	];	

	/**
	 * khai báo danh sách các thuộc tính được gán hàng loạt.
	 */
	const FILLED_FILEDS = [self::TITLE, self::DESCRIPTION, self::TYPE, self::IMAGE_PATH, self::HOME_ID];

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', self::HOME_ID];

	const PREFIX = 'room';
	const ROUTE_PREFIX = ADMIN_PREFIX . '/ahome';
}
