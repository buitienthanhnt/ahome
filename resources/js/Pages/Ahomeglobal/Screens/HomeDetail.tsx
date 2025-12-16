
import { FunctionComponent, } from 'react';
import { HomeDetail as HomeDetailType } from '../types/Home.d';
import { RoomDetail, } from '../types/Room';
import { Head, router } from '@inertiajs/react';
import { HomeIcon, MapPinIcon, SparklesIcon } from '@heroicons/react/24/solid';
import Urls from '../netWork/Urls';
import { usePageMessage } from '../hooks';
import { RoomItem, RoomTime } from '../Components/Room';
import { Rating } from "@material-tailwind/react";
import FlashMessage from '../Components/FlashMessage';
import Location from '../Components/Location';

type Props = {
	homeDetail: HomeDetailType,
	roomSelected: RoomDetail,
}
const HomeDetail: FunctionComponent<Props> = ({ homeDetail, }) => {
	const messages = usePageMessage();

	const rate = homeDetail?.attr.find(i => i.key === 'rate');
	const location = homeDetail?.attr.find(i => i.key === 'location');
	const homeDisable = homeDetail.order_times.map(t => {
		return t?.room_ids.length === homeDetail?.rooms.length ? t.date : undefined;
	}).filter(item => item !== undefined);

	if (!homeDetail) {
		return null;
	}

	return (
		<>
			<Head>
				<title>{homeDetail.name}</title>
			</Head>
			<div className='container mx-auto p-2 rounded-md min-h-screen space-y-2 py-4'>
				<div className='bg-gray-100 p-4 flex space-x-3 rounded-md items-center ' onClick={() => {
					router.get(Urls.homeList);
				}}>
					<HomeIcon className='font-semibold text-blue-400 text-2xl size-8'></HomeIcon>
					<h2 className='font-semibold text-blue-400 text-2xl'>Ahome Global</h2>
				</div>
				<div className='flex flex-col gap-y-2 bg-gray-100 p-1 md:p-2 rounded-md'>
					<p className='text-2xl font-bold text-black'>Hotel: {homeDetail.name}</p>
					<div className='flex space-x-1'>
						<MapPinIcon className="size-5 text-gray-800"></MapPinIcon>
						<p className='text-lg font-semibold '>District: {homeDetail.district}</p>
					</div>
					<div className='flex space-x-1'>
						<SparklesIcon className="size-5 text-gray-800"></SparklesIcon>
						<p className='text-md font-semibold text-green-400'>Description: {homeDetail.description}</p>
					</div>
					{
						rate && <Rating value={Number(rate.value as unknown as number > 5 ? 5 : rate.value)} placeholder={'rate'}
							onResize={undefined}
							onResizeCapture={undefined}
							readonly
						/>
					}
				</div>
				<div className='h-[1px] bg-black'></div>
				<FlashMessage message={messages}></FlashMessage>
				{homeDetail.rooms.length ?
					<div className='space-y-2 grid grid-cols-1 lg:grid-cols-5 gap-x-1 bg-gray-100 p-1 md:p-2 rounded-md'>
						<div className='col-span-2 rounded-md space-y-2'>
							<p className='text-xl font-semibold '>List rooms of the hotel:</p>
							<div className='flex flex-col gap-y-2'>
								{homeDetail.rooms.map(room => <RoomItem room={room} key={room.id.toString()}></RoomItem>)}
							</div>
						</div>
						<div className='col-span-3'>
							<RoomTime allDisable={homeDisable}></RoomTime>
						</div>
					</div> : (
						<div className='bg-white flex justify-center items-center rounded-md p-1 lg:p-4'>
							<p className='font-semibold text-xl text-red-500 italic'>the hotel not active!</p>
						</div>
					)}
				{location && <div className='grid grid-cols-1 lg:grid-cols-2 bg-gray-100 rounded-md'>
					<div className='col-span-1 lg:visible'></div>
					<Location
						style='w-[420px] h-[360px] border p-1'
						url={location.value}></Location>
				</div>}
			</div>
		</>
	);
}

export default HomeDetail;