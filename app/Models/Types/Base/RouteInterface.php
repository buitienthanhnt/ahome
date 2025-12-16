<?php

namespace App\Models\Types\Base;

interface RouteInterface extends ShareInterface
{
	const ROUTE_ACTION = [
		'list' => '',
		'create' => 'create',
		'register' => 'register',
		'detail' => 'detail/{id}',
		'delete' => 'delete/{id}',
		'edit' => 'edit/{id}',
		'update' => 'update/{id}',
	];
}
