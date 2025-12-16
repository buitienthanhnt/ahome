<?php

namespace App\Models\Types;

interface TagInterface{
	const TABLE_NAME = 'tags';

	const KEY = 'key';
	const VALUE = 'value';
	const TARGET_ID = 'target_id';
	const TYPE = 'type';

	const FILLED_FILEDS = [self::KEY, self::VALUE, self::TYPE, self::TARGET_ID];

	const HIDDEN_FIELDS = ['deleted_at', 'created_at', 'updated_at'];
}