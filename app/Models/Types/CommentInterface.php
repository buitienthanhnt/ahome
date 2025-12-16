<?php

namespace App\Models\Types;

interface CommentInterface{
	const TABLE_NAME = 'comments';

	const ID = 'id';
	const ACTIVE = 'active';
	const TYPE = 'type';
	const USER_ID = 'user_id';
	const CONTENT = 'content';
	const PARENT_ID = 'parent_id';
	const TARGET_ID = 'target_id'; // example for page_id

	const FILLED_FILEDS = [self::TYPE, self::USER_ID, self::CONTENT, self::PARENT_ID, self::TARGET_ID];
	const HIDDEN_FIELDS = [self::USER_ID, self::ACTIVE, self::TYPE];

	const MODEL_TYPE = 'comment';
}