import { AttrInterface } from "./Attr"
import { OrderTimeInterface } from "./OrderTime"
import { RoomItem } from "./Room"

export type HomeItem = {
	id: number,
	name: string,
	description?: string,
	district?: string,
	image_path?: string,
}

export type HomeDetail = HomeItem & {
	rooms: RoomItem[],
	order_times: OrderTimeInterface[],
	attr: AttrInterface[],
}