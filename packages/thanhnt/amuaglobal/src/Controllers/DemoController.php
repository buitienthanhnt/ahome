<?php

namespace Thanhnt\Amuaglobal\Controllers;

use App\Http\Controllers\Controller;

final class DemoController extends Controller
{
	public function __construct() {}

	/**
	 * demo for test action from controller
	 */
	public function docData(): string
	{
		return 123;
	}
}
