<?php

namespace App\Models\Types;

interface DesignInterface{
	const ID = 'id';
	const NAME = 'name';
	const TYPE = 'type';
	const VALUE = 'value';

	const FILLED_FILEDS = [self::NAME, self::TYPE, self::VALUE];
}