<?php

namespace App\Models\Types;

interface ViewSourceInterface
{

	/**
	 * define model attribute:
	 */
	const ID = 'id';
	const TARGET_ID = 'target_id';
	const VALUE = 'value';
	const TYPE = 'type';

	const TYPE_LIKE = 'LIKE';
	const TYPE_HEART = 'HEART';
	const TYPE_FIRE = 'FIRE';

	const ACTION_ADD = 'add';
	const ACTION_SUB = 'sub';

	/**
	 * define fields of form model
	 */
	const FORM_FIELDS = [];
	/**
	 * define list attributes for auto mash value.
	 */
	const FILLED_FIELDS = [self::TARGET_ID, self::TYPE, self::VALUE];
}
