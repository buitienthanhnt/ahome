<?php
namespace Thanhnt\Ahomeglobal\Models\Types;

interface OrderInterface{
	const TABLE_NAME = 'orders';

	const ID = 'id';
	const ROOM_ID = 'room_id';
	const HOME_ID = 'home_id';
	const DATE_FROM = 'date_from';
	const DATE_TO = 'date_to';
	const SELECTED_TIME = 'selected_time';
	const STATUS = 'status'; // complete, success, cancel,

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', self::HOME_ID, self::ROOM_ID, ];
}