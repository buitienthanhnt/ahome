import { RoomDetail } from "../types/Room";
import usePageProps from "./usePageProps";

const useRoomOrders = ()=>{
	const {roomSelected} = usePageProps();
	return roomSelected as RoomDetail;
}

export {useRoomOrders}