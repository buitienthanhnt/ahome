
import { useCallback } from 'react';
import { RoomItem as RoomItemType } from '../types/Room';
import { router, useRemember } from '@inertiajs/react';
import { CustomTimeTable } from '../Components/CustomCalenda';
import { Button, } from '@material-tailwind/react';
import { XMarkIcon, } from '@heroicons/react/24/solid';
import { usePageMessage, useRoomOrders } from '../hooks';
import { CubeIcon, CurrencyDollarIcon, InformationCircleIcon } from '@heroicons/react/16/solid';
import { UsersIcon } from '@heroicons/react/16/solid';

const RoomItem = ({ room }: { room: RoomItemType }) => {
	const booked = useRoomOrders();
	const messages = usePageMessage();

	const onClickRoom = (room: RoomItemType) => {
		const urlParams = new URLSearchParams(window.location.search);
		if (urlParams.get('room') === room.id.toString()) {
			router.visit(window.location.pathname, {
				method: 'get',
			});
		} else {
			router.visit(window.location.href, {
				method: 'get',
				data: { room: room.id },
				only: messages ? [] : ['roomSelected'], // clear app props if has flash mesasge.
				// preserveState: true, // for remenber old state of page(can save for: dateSelected)
			})
		}
	}

	return (
		<div className={`p-0.5 rounded-md flex gap-x-2 shadow-md ${booked?.id === room.id ? 'bg-green-400' : 'bg-deep-purple-100'}`} onClick={() => {
			onClickRoom(room);
		}}>
			<div>
				<img src={room.image_path} className='min-w-32 w-32 aspect-square rounded-md' alt="" />
			</div>
			<div>
				<p className='font-semibold text-purple-500'>Room: {room.title}</p>
				<div className='flex items-center gap-x-1'>
					<CubeIcon className='size-5 text-gray-800'></CubeIcon>
					<p className='text-black font-semibold text-sm md:text-md'>{room.description}</p>
				</div>
				<div className='flex items-center gap-x-1'>
					<CurrencyDollarIcon className='size-5 text-gray-800'></CurrencyDollarIcon>
					<p className='text-black font-semibold text-sm md:text-md'>{room.price} 000 VND</p>
				</div>
				<div className='flex items-center gap-x-1'>
					<UsersIcon className='size-5 text-black'></UsersIcon>
					<p className='font-semibold text-sm md:text-md'>{room.type}</p>
				</div>
			</div>
		</div>
	)
}

const RoomTime = ({ allDisable }: { allDisable?: string[] }) => {
	const booked = useRoomOrders();
	const bookedDate = booked?.booked_dates || [];

	const [dateSelected, setDateSelected] = useRemember<Date[]>([], 'Ahomeglobal/HomeDetail');

	const onSubmitOrder = useCallback(() => {
		const selectedValues = dateSelected.map(d => [
			d.getFullYear(),
			d.getMonth() + 1 >= 10 ? d.getMonth() + 1 : '0' + (d.getMonth() + 1).toString(),
			d.getDate() < 10 ? '0' + d.getDate().toString() : d.getDate(),
		].join('-'));

		router.visit(window.location.href, {
			method: 'post',
			data: {
				values: selectedValues,
			},
		})

	}, [dateSelected])

	return (
		<div className='space-y-2 grid grid-col-1 md:grid-cols-3 gap-1 lg:gap-2'>
			<div className='space-y-1 md:col-span-2'>
				<CustomTimeTable
					selected={dateSelected}
					onChange={setDateSelected}
					minDate={new Date()}
					// maxDate={new Date(2025, 11, 19)}
					disable={[...bookedDate, ...allDisable].map(s => new Date(s))}
				></CustomTimeTable>
			</div>
			<div>
				<span className='text-lg font-semibold'>Selected room: {booked?.title || 'Random room'}</span>
				<div className='col-span-1 flex flex-col space-y-1'>
					{!!dateSelected.length && <div className='flex justify-between items-center'>
						<span className='text-xl font-semibold'>Your selected:</span>
						<span className='bg-orange-400 rounded-full p-2' onClick={() => { setDateSelected([]); }}>
							<XMarkIcon className='size-4 text-white font-extrabold'></XMarkIcon>
						</span>
					</div>}
					<div className='grid grid-cols-2 gap-1 md:gap-2'>
						{dateSelected.map((date, index) => {
							return (
								<div key={index} className='p-1 bg-blue-400 rounded-md flex justify-center items-center content-center'>
									<span className='text-black font-semibold'>{date.getFullYear()}-{date.getMonth() + 1}-{date.getDate()}</span>
								</div>
							)
						})}
					</div>
					{!!dateSelected.length && <Button placeholder={'view selected'} onClick={onSubmitOrder}>
						<span>Order the selected</span>
					</Button>}
				</div>
			</div>
		</div>
	)
}

export { RoomItem, RoomTime };