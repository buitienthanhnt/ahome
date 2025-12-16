<?php

namespace App\Models\Types;

interface PageContentInterface
{
	const TABLE_NAME = 'page_contents';

	const ID = 'id';
	const KEY = 'key';
	const VALUE = 'value';
	const EXTEND_VALUE = 'extend_value';
	const TYPE = 'type';
	const PAGE_ID = 'page_id';

	const FORM_FIELDS = [];
	const DEFAULT_FIELD_TYPE = [
		FormInterface::TYPE_CHECKBOX,
		FormInterface::TYPE_TEXT,
		FormInterface::TYPE_TEXTAREA,
		FormInterface::TYPE_TEXTEDITOR,
		FormInterface::TYPE_FILE,
		FormInterface::TYPE_IMAGE_CHOOSE,
		FormInterface::TYPE_NUMBER,
		'video',
		'carousel',
	];
	const FILLED_FILEDS = [
		self::KEY,
		self::VALUE,
		self::EXTEND_VALUE,
		self::PAGE_ID,
		self::TYPE,
	];

	const HIDDEN_FIELDS = ['deleted_at', 'created_at', 'updated_at'];

	const SAVED_IMAGE_FOLDER = 'pageContent';
	/**
	 * var key for filter page 
	 */
	const TYPE_FILTER_KEY = 'type';
}
