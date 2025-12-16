import { FunctionComponent, useState } from "react";
import { Head, Link, router, useRemember } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";
import { listDateToArrayString } from "../Helper/DateTimeHelper";
import { HomeItem as HomeItemType } from "../types/Home";
import { RoomItem as RoomItemType } from "../types/Room";
import { PagePaginate } from "../types/Paginate";
import { DropdownMenu, Paginate, CustomTimeTable, RoomItemGrid, HomeMap } from "../Components";
import { FingerPrintIcon, MapPinIcon, XMarkIcon } from "@heroicons/react/24/solid";
import { SparklesIcon } from "@heroicons/react/24/solid";


const HomeItem = ({ home, filters }: { home: HomeItemType, filters?: any }) => {

	return (
		<Link className="bg-blue-gray-200 shadow-md p-1 rounded-md flex gap-x-1 md:gap-x-4"
			queryStringArrayFormat={'brackets'}
			href={sprintf(Urls.homeDetail, [home.id])} data={filters ? { filters } : undefined}>
			<div>
				<img src={home.image_path} alt="avata hotel" className="max-w-40 md:max-w-60 rounded-md" />
			</div>
			<div className="flex flex-col justify-between">
				<div>
					<h4 className="text-md lg:text-2xl text-blue-700 font-bold ">{home.name}</h4>
					<div className="flex gap-x-1 items-center">
						<SparklesIcon className="size-5 text-gray-800"></SparklesIcon>
						<p className="text-sm md:text-md lg:text-lg font-semibold">{home.description}</p>
					</div>
				</div>
				<div className="flex">
					<MapPinIcon className="size-5 text-purple-500"></MapPinIcon>
					<p className="italic text-purple-600 font-semibold text-sm md:text-md">{home.district}</p>
				</div>
			</div>
		</Link>
	)
}

type Props = {
	homes: PagePaginate,
	rooms: PagePaginate,
	filters: { [key: string]: string | string[] | any },
	allFilters: any[],
}

const HomeList: FunctionComponent<Props> = ({ homes, rooms, filters, allFilters }) => {

	return (
		<>
			<Head title="home"></Head>
			<div className="container mx-auto p-2 space-y-4">
				<div className=" bg-blue-gray-300 min-h-36 rounded-md p-4"></div>
				{/* <HomeMap></HomeMap> */}
				<div className="grid lg:grid-cols-5 bg-white lg:space-x-4 space-y-2 lg:space-y-0">
					<div className="col-span-1 lg:col-span-2">
						<HomeFilter filters={filters} allFilters={allFilters}></HomeFilter>
					</div>
					<div className="col-span-1 lg:col-span-3 flex flex-col gap-y-2">
						<p className="text-xl font-bold text-purple-800">List of Hotels total: {homes.total}</p>
						{homes?.data.map(home => <HomeItem home={home as HomeItemType} filters={filters} key={home.id.toString()}></HomeItem>)}
						<Paginate pageSize={homes.last_page} currentPage={homes.current_page}></Paginate>
					</div>
				</div>
				<div className='h-[1px] bg-black my-2'></div>
				{rooms && <div className="gap-2">
					<p className="text-xl font-bold text-purple-800">List of rooms total: {rooms.total}</p>
					<h3 className="text-lg font-semibold text-purple-300"></h3>
					<div className="lg:p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
						{rooms?.data.map(room => <div key={room.id}>
							<RoomItemGrid room={room as unknown as RoomItemType}></RoomItemGrid>
						</div>)}
					</div>
					<Paginate pageSize={rooms.last_page} currentPage={rooms.current_page} pageName="room_page"></Paginate>
				</div>}
			</div>
		</>
	)
}

const HomeFilter = ({ filters, allFilters }) => {
	const [dateSelected] = useState<Date[]>(!!filters && filters?.dates ? filters?.dates.map((d: string) => new Date(d)) || [] : []);

	const [formState, setFormState] = useRemember({
		search: filters?.district || '',
	}, 'page.search')

	const onFilterSubmit = (type: string, item: { ley: string, value: string }) => {
		const newFilter = {
			...filters,
			[type]: item.value  // thêm khóa và giá trị mới cho bộ lọc
		};
		/**
		 * loại trừ khóa khi khóa đó chọn lại lần 2 cùng giá trị(bỏ chọn)
		 */
		if (filters?.[type] !== undefined && filters?.[type] === item.value) {
			delete newFilter[type];
		}
		// gửi yêu cầu thủ công.
		router.visit(window.location.pathname, {
			method: 'post',
			data: {
				filters: newFilter,
				page: undefined
			},
			queryStringArrayFormat: 'indices',
			replace: true,
			preserveScroll: true,
			// preserveUrl: true,
			// forceFormData: true,
		})
	}

	const onDateSelect = (value: Date[]) => {
		// chuyển hướng thủ công.
		router.visit(window.location.pathname, {
			method: 'post',
			data: {
				filters: {
					...filters,
					dates: value.length ? listDateToArrayString(value) : undefined,
				},
				page: undefined
			},
			preserveScroll: true,
		})
	}

	const searchLocation = (isClear = false) => {
		// chuyển hướng thủ công.
		router.visit(window.location.pathname, {
			method: 'post',
			data: {
				filters: {
					...filters,
					district: isClear ? undefined : formState.search,
				},
				page: undefined
			},
			preserveScroll: true,
		})
	}

	if (!allFilters) { return null; }

	return (
		<div className="flex flex-col gap-x-2 gap-y-3">
			<div className='flex gap-4 items-center'>
				{!!formState.search && <XMarkIcon width={36} height={36} className='hover:rotate-12 hover:text-orange-800' onClick={() => {
					searchLocation(true)
				}}></XMarkIcon>}
				<input type="text" value={formState.search}
					onChange={e => setFormState(old => { return { ...old, search: e.target.value } })}
					placeholder='Search By Location'
					className='rounded-md w-full md:w-96'
				/>
				{formState.search && <div onClick={() => { searchLocation(false) }}>
					<FingerPrintIcon width={36} height={36} className='hover:scale-110 text-gray-500 hover:text-black'></FingerPrintIcon>
				</div>}
			</div>
			{allFilters.map((filter, index) => {
				return <DropdownMenu key={index} type={filter.key} label={filter.label} data={filter.data} onChange={onFilterSubmit}></DropdownMenu>
			})}
			<CustomTimeTable
				selected={dateSelected}
				onChange={onDateSelect}
				minDate={new Date()}
			></CustomTimeTable>
		</div>
	)
}

export default HomeList;