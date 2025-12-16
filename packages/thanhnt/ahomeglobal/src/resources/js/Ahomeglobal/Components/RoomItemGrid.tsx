import { FunctionComponent } from "react";
import { RoomItem } from "../types/Room"
import { Link } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";
import { CubeIcon, CurrencyDollarIcon, UsersIcon } from "@heroicons/react/24/solid";

type Props = {
	room: RoomItem;
}

const RoomItemGrid: FunctionComponent<Props> = ({ room }) => {
	return (
		<Link href={sprintf(Urls.homeDetail, [room.home_id])} data={{ room: room.id }}
			className="p-1 h-full bg-blue-gray-100 rounded-md shadow-lg min-h-10 flex gap-x-2 hover:bg-blue-gray-200"
		>
			<div>
				<img src={room.image_path} alt="room avata" className="max-w-40 md:max-w-60 h-full rounded-md" />
			</div>
			<div className="flex flex-col justify-between md:justify-start">
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
		</Link>
	)
}

export default RoomItemGrid;