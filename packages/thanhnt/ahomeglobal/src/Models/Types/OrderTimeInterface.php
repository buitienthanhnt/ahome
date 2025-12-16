<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface OrderTimeInterface{
	const TABLE_NAME = 'order_times';

	const ID = 'id';
	const HOME_ID = 'home_id';
	const ROOM_IDS = 'room_ids';
	const ORDER_IDS = 'order_ids';
	const DATE = 'date';

	const FILLED_FILEDS = [self::HOME_ID, self::ORDER_IDS, self::ROOM_IDS, self::DATE];

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', 'order_ids'];

}