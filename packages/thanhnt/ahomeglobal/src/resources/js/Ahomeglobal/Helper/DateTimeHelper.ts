/**
 * Date -> local format
 * return format: mm/dd/YYYY(client use for calenda) from Date value
 */
const convertLocalDate = (date: string | Date): string => {
	const currentDate = typeof (date) === 'string' ? new Date(date) : date;
	return (currentDate.getMonth() + 1) + '/' + (currentDate.getDate() < 10 ? '0' + currentDate.getDate() : currentDate.getDate()) + '/' + currentDate.getFullYear()
}

/**
 * server format -> local format
 * format date from "YYYY-MM-DD"(server format) to local: "mm/dd/YYYY"(client use for calenda string)
 */
const formatIsoStringToLocal = (date: string): string => {
	/**
	* format date from "YYYY-MM-DD" to local: "mm/dd/YYYY"
	* error by: https://github.com/wix/react-native-calendars/issues/1319
	*/
	const dAttr = date.slice(0, 10).split("-");
	return [dAttr[1], dAttr[2], dAttr[0]].join('/');
}

/**
 * Date[] local format -> string[] server format
 * format from Date[](client format) to [YYYY-MM-DD](server format
 */
const listDateToArrayString = (dates: Date[]): string[] => {
	return dates.map(d => [
		d.getFullYear(),
		d.getMonth() + 1 >= 10 ? d.getMonth() + 1 : '0' + (d.getMonth() + 1).toString(),
		d.getDate() < 10 ? '0' + d.getDate().toString() : d.getDate(),
	].join('-'));
}

export { convertLocalDate, formatIsoStringToLocal, listDateToArrayString };