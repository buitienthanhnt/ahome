export interface OrderTimeInterface {
	id: number;
	date: string;
	home_id: number;
	room_ids?: number[];
	order_ids?: number[];
}