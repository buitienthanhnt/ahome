<?php

namespace Thanhnt\Ahomeglobal\Helper;

final class DateTimeHelper
{
	/**
	 * sort array string date time for asc|desc
	 * @param array $dateArray
	 * @param string $type
	 * @return string[]
	 */
	public function sortArrayDateString(array $dateArray, string $type = 'asc')
	{
		// sort by usort and callback of object function: use an array with 2 param [$this instance and, function name] 
		usort($dateArray, [$this, $type === 'asc' ? 'compareDatesAsc': "compareDatesDesc"]);
		return $dateArray;
	}

	/**
	 * sort  desc
	 * @param string $date1
	 * @param string $date2
	 * @return int
	 */
	protected function compareDatesDesc(string $date1, string $date2)
	{
		// return number
		return strtotime($date2) - strtotime($date1); // Note the order change
	}

	/**
	 * sort asc
	 * @param string $date1
	 * @param string $date2
	 * @return int
	 */
	protected function compareDatesAsc(string $date1, string $date2)
	{
		// return number
		return strtotime($date1) - strtotime($date2); // Note the order change
	}
}
