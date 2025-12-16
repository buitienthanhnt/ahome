<?php

namespace Thanhnt\Acarglobal\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Thanhnt\Acarglobal\Models\Car;

final class AcarController extends Controller
{

	public function addCar()
	{
		Car::insert([
			'key' => '30x-22256',
			'suspension' => 'audi',
			'type' => 'q7',
			'km' => 17000,
			'year' => Carbon::create(2020),
			'vin' => 'klasnbdjbuabsdipp',
		]);

		return Car::all();
	}


	// public function __invoke()
	// {
	// 	return ('this is message from AcarController __invoke function');
	// 	throw new \Exception('Not implemented');
	// }
}
