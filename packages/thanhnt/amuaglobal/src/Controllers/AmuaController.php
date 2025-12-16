<?php

namespace Thanhnt\Amuaglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class AmuaController extends Controller
{
	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	/**
	 * this request call artisan for clear cache of page 
	 */
	public function clearCache(): string
	{
		/**
		 * call artisan command line in request process
		 * clear cache for app.
		 */
		$status = Artisan::call('cache:clear');
		return 'cache clear success!';
	}
}
