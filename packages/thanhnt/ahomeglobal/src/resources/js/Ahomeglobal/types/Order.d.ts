import { HomeItem } from "./Home.d";
import { RoomItem } from "./Room";

export type StatusValue = 'complete' | 'success' | 'cancel';


export interface OrderItemInterface {
	id: number;
	status: StatusValue;
	date_from: string;
	date_to: string;
	selected_time: string[];
};

export interface OrderDetailInterface extends OrderItemInterface {
	room: RoomItem;
	home: HomeItem;
}