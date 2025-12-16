<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface AttrInterface{
	const TABLE_NAME = 'ahome_attr';

	const ID = 'id';
	const SOURCE_ID = 'source_id';
	const TYPE = 'type';
	const KEY = 'key';
	const VALUE = 'value';

	const FILLED_FIELDS = [self::SOURCE_ID, self::TYPE, self::KEY, self::VALUE];

	const HIDDEN_FIELDS = [];
}