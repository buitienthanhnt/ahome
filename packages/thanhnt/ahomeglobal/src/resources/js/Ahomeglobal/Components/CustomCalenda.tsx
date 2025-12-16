import { createContext, FunctionComponent, useCallback, useContext, useMemo, useState } from 'react';
import { ArrowLeftCircleIcon, ArrowLeftIcon, ArrowRightCircleIcon, ArrowRightIcon, CheckCircleIcon } from '@heroicons/react/24/solid';

type NavigationMonthProp = {
	forcusDate: Date;
	onNavigate?: (date: Date) => void;
	minDate?: Date;
	maxDate?: Date;
};

const NavigationMonth: FunctionComponent<NavigationMonthProp> = ({ forcusDate, onNavigate }: NavigationMonthProp) => {
	const { minDate, maxDate } = useContext(CustomTimeContext);
	const year = forcusDate?.getFullYear();
	const month = forcusDate?.getMonth(); // Sẽ bị sai(nhận today) khi focusDate.getMonth() =0;

	const minMonth = minDate?.getMonth(); const minYear = minDate?.getFullYear();
	const maxMonth = maxDate?.getMonth(); const maxYear = maxDate?.getFullYear();


	const nextMonth = useCallback(() => {
		const nextYear = month === 11 ? year + 1 : year;
		const nextMonth = month === 11 ? 0 : month + 1;
		if (nextYear > maxYear || (maxYear === nextYear && nextMonth > maxMonth)) {
			return;
		}
		onNavigate(new Date(nextYear, nextMonth, 15));
	}, [month, year, maxYear, maxMonth]);

	const prevMonth = useCallback(() => {
		const nextYear = month === 0 ? year - 1 : year;
		const nextMonth = month === 0 ? 11 : month - 1;
		if (nextYear < minYear || (nextYear === minYear && nextMonth < minMonth)) {
			return;
		}
		onNavigate(new Date(nextYear, nextMonth, 15));
	}, [month, year, minYear, minMonth]);

	const prevYear = useCallback(() => {
		if (year - 1 < minYear) { return; }
		onNavigate(new Date(year - 1, month, 15));
	}, [month, year, minYear]);

	const nextYear = useCallback(() => {
		if (year + 1 > maxYear) { return; }
		onNavigate(new Date(year + 1, month, 15));
	}, [month, year, maxYear])

	return (
		<div className='flex bg-white p-2 justify-between items-center rounded-md px-4'>
			<ArrowLeftIcon width={24} height={24} onClick={prevYear} title='prev year'></ArrowLeftIcon>
			<ArrowLeftCircleIcon width={24} height={24} onClick={prevMonth} title='prev month'></ArrowLeftCircleIcon>
			<p className='text-black text-xl font-semibold'>{month + 1}/{year}</p>
			<ArrowRightCircleIcon width={24} height={24} onClick={nextMonth} title='next month'></ArrowRightCircleIcon>
			<ArrowRightIcon width={24} height={24} onClick={nextYear} title='next year'></ArrowRightIcon>
		</div>
	)
}

type DateItemProp = {
	date: Date;
}

const DateItem: FunctionComponent<DateItemProp> = ({ date }) => {
	const { listDates, minDate, maxDate, disableString, onClickDate, selectedString, today, } = useContext(CustomTimeContext);
	const isDisable = disableString.includes(date.toLocaleDateString());
	const isOutOfDate = useMemo(() => {
		if (minDate && new Date(minDate.getFullYear(), minDate.getMonth(), minDate.getDate()) > date) {
			return true;
		}
		if (maxDate && new Date(maxDate.getFullYear(), maxDate.getMonth(), maxDate.getDate()) < date) {
			return true;
		}

		return false;
	}, [minDate, maxDate, date]);

	return (
		<div
			className={`p-1 py-2 justify-center items-center flex rounded-xl relative ${listDates[10].getMonth() !== date.getMonth() ? 'opacity-75' : ''} ${today.toLocaleDateString() === date.toLocaleDateString() ? 'bg-purple-400' : 'bg-green-200'} ${isOutOfDate ? 'opacity-50' : ''} ${isDisable ? '!bg-red-500' : ''}`}
			onClick={() => { onClickDate(date) }}
		>
			<span className={`font-semibold ${[0, 6].includes(date.getDay()) ? 'text-red-500' : ''} ${isDisable ? '!text-black' : ''} `}>
				{date.getDate()}
			</span>
			{selectedString?.includes(date.toLocaleDateString()) &&
				<CheckCircleIcon className='size-4 md:size-5 text-blue-500 absolute right-1 top-1'></CheckCircleIcon>
			}
		</div>
	)
}

const CustomTimeContext = createContext<{
	listDates: Date[],
	onClickDate: (date: Date) => void,
	today: Date,
	disableString?: string[],
	selectedString?: string[],
	minDate?: Date,
	maxDate?: Date,
}>({ listDates: [], today: new Date(), onClickDate: () => { }, });

type CustomTimeProp = {
	selected?: Date[];
	onChange?: (values: Date[]) => void;
	minDate?: Date;
	maxDate?: Date;
	disable?: Date[];
};

const CustomTimeTable: FunctionComponent<CustomTimeProp> = ({ selected, onChange, minDate, maxDate, disable }) => {
	const today = new Date();
	const [focusDate, setFocusDate] = useState(new Date());
	const selectedString = selected?.map(s => s.toLocaleDateString()) || [];
	const disableString = disable?.map(s => s.toLocaleDateString()) || [];

	/**
	 * lấy 35 || 42 ngày
	 * 1. lấy ngày hiện tại
	 * 2. lấy tháng hiện tại
	 * 3. lấy thứ của ngày 1 trong tháng hiện tại.
	 * 4, xét thứ của bước 3(chỉ có thể nằm ở hàng 1): nếu vị trí [MON] thì trước nó không có ngày nào
	 * 												   nếu vị trí [SUN] thì trước nó có 6
	 * 												   tương tự với các vị trí khác.
	 * 5. xét tháng có bao nhiêu ngày. trường hợp 
	 * 6. xét các ngày tiếp theo: nếu số ngày trước đó cộng với số ngày trong tháng > 35 thì sẽ có 6 hàng với 42 ngày
	 * 7. xét ngày cuối cùng trong tháng: nếu vào [SUN] thì sau nó không có ngày nào,
	 * 									: nếu vào [MON] thì sau nó có 6 ngày
	 * 									: tương tự với số ngày còn lại.			
	 * 8. gộp số ngày phía trước(1) + số ngày tuần tự của tháng + số ngày phía sau.
	 */
	const listDates = useMemo(() => {
		let countDays: number = 35;
		const toDay: Date = focusDate; // new Date('2026-01-04'); //
		const firstDayOfWeek: Date = new Date(toDay.getFullYear(), toDay.getMonth(), 1); // 1=mon;2=tue;3=wed;4=thu;5=fri;6=sat;0=sun
		let beforeDays: number = firstDayOfWeek.getDay() === 0 ? 6 : firstDayOfWeek.getDay() - 1;

		const monthDays = new Date(toDay.getFullYear(), toDay.getMonth() + 1, 0).getDate();
		if (monthDays + beforeDays > 35) {
			countDays = 42;
		}
		const lastDayOfMonth = new Date(toDay.getFullYear(), toDay.getMonth() + 1, 0); // if date = 0 so getMonth() need +1
		const afterDays = lastDayOfMonth.getDay() === 0 ? 0 : 7 - lastDayOfMonth.getDay();

		let arrayDays: Date[] = [];
		for (let index = beforeDays - 1; index >= 0; index--) {
			arrayDays.push(new Date(toDay.getFullYear(), toDay.getMonth(), 0 - index))
		}

		for (let j = 1; j <= lastDayOfMonth.getDate(); j++) {
			arrayDays.push(new Date(toDay.getFullYear(), toDay.getMonth(), j));
		}

		for (let k = 1; k <= afterDays; k++) {
			arrayDays.push(new Date(toDay.getFullYear(), toDay.getMonth(), lastDayOfMonth.getDate() + k));
		}
		return arrayDays;
	}, [focusDate]);

	const onClickDate = useCallback((date: Date) => {
		if (minDate && new Date(minDate.getFullYear(), minDate.getMonth(), minDate.getDate()) > date) {
			return;
		}

		if (maxDate && new Date(maxDate.getFullYear(), maxDate.getMonth(), maxDate.getDate()) < date) {
			return;
		}

		if (disableString.includes(date.toLocaleDateString())) {
			if (date.getMonth() !== listDates[10].getMonth()) {
				setFocusDate(date);
			}
			return;
		}
		/**
		 * nếu cùng trong tháng đang hiển thị thì chọn hoặc bỏ chọn ngày đó
		 * nếu khác tháng thì chuyển lịch sang tháng đó và chưa chọn ngày đó.
		 */
		if (date.getMonth() === listDates[10].getMonth()) {
			onChange(selectedString?.includes(date.toLocaleDateString()) ? [...selected.filter(i => {
				return i.toLocaleDateString() !== date.toLocaleDateString();
			})] : [...selected, date]);
			return;
		}
		setFocusDate(date);
	}, [listDates, onChange, selectedString])

	return (
		<CustomTimeContext.Provider value={{
			listDates: listDates,
			onClickDate: onClickDate,
			disableString: disableString,
			selectedString: selectedString,
			minDate: minDate,
			maxDate: maxDate,
			today: today,
		}}>
			<NavigationMonth onNavigate={setFocusDate} forcusDate={focusDate}></NavigationMonth>
			<div className='bg-white p-1 lg:p-2 grid grid-cols-7 gap-1 lg:gap-2 rounded-md'>
				<div className='bg-blue-gray-300 p-1 justify-center items-center flex rounded-md'>
					<span>MON</span>
				</div>
				<div className='bg-blue-gray-300 p-1 justify-center items-center flex rounded-md'>
					<span>TUE</span>
				</div>
				<div className='bg-blue-gray-300 p-1 justify-center items-center flex rounded-md'>
					<span>WEB</span>
				</div>
				<div className='bg-blue-gray-300 p-1 justify-center items-center flex rounded-md'>
					<span>THU</span>
				</div>
				<div className='bg-blue-gray-300 p-1 justify-center items-center flex rounded-md'>
					<span>FRI</span>
				</div>
				<div className='bg-blue-gray-300 p-1 justify-center items-center flex rounded-md'>
					<span>SAT</span>
				</div>
				<div className='bg-blue-gray-300 p-1 justify-center items-center flex rounded-md'>
					<span>SUN</span>
				</div>
				{listDates.map((item: Date, index) => <DateItem key={index.toString()} date={item}></DateItem>)}
			</div>
			{/* <div className='flex flex-col items-center bg-white p-1 rounded-md md:py-4 font-semibold md:font-bold text-blue-600'>
				<p>ISO:{focusDate.toISOString().slice(0, 10)}&nbsp; &nbsp; &nbsp; Local:{focusDate.toLocaleDateString()}</p>
				{selected.map(s => <p key={s.toLocaleDateString()}>{s.toLocaleDateString()}</p>)}
			</div> */}
		</CustomTimeContext.Provider>
	)
}

export { CustomTimeTable, NavigationMonth }