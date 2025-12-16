import { HomeItem } from "./Home.d";
import { OrderItemInterface } from "./Order";

type TypeValue = "one" | "two" | "three" | "four" | "five" | "six" | "all";

export type RoomItem = {
	id: number,
	title: string,
	description: string,
	type: TypeValue,
	home_id: number,
	created_at: string,
	image_path?: string,
	price: string,
}

export type RoomDetail = RoomItem & {
	home: HomeItem,
	// orders: OrderItemInterface[],
	booked_dates: string[],
}