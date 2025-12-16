<?php

namespace App\Models\Types\Base;

interface TimestampInterface extends ShareInterface
{
	// const CREATED_AT = 'created_at';
	// const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
}
