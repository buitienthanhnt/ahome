import { Link } from "@inertiajs/react"
import { ArrowLeftIcon, ArrowRightIcon } from "@heroicons/react/24/solid";
import { PaginateProp } from "../types/Paginate";

const Paginate = ({ pageSize, currentPage, url = window.location.href, pageName = 'page' }: PaginateProp) => {
	if (pageSize === 1) { return null; }

	if (pageSize < 6) {
		return (
			<div className="justify-center content-center flex p-4 gap-x-2">
				{(() => {
					const listPage = [];
					for (let index = 1; index <= pageSize; index++) {
						listPage.push(<Link prefetch href={url} preserveScroll key={index} data={{
							[pageName]: index,
						}}>
							<span
								className={`p-2 px-4 bg-green-500 rounded-[20px] ${currentPage === index ? 'text-white' : ''} justify-center content-center text-xl font-bold hover:text-orange-500`}
							>{index}</span>
						</Link>)
					}
					return listPage;
				})()}
			</div>
		)
	}

	return (
		<div className="justify-center content-center flex p-4 gap-x-2">
			{currentPage - 2 > 1 && <Link href={url} prefetch={['hover',]} cacheFor="1m"
				data={{
					[pageName]: currentPage - 5 > 1 ? currentPage - 5 : 1
				}}>
				<div className="p-2 px-3 md:px-4 bg-green-500 rounded-[28px] justify-center content-center">
					<ArrowLeftIcon className="h-7 w-5"></ArrowLeftIcon>
				</div>
			</Link>}

			{(() => {
				const listPage = [];
				for (let index = (currentPage - 2 < 1 ? 1 : currentPage - 2); index <= (currentPage + 2 > pageSize ? pageSize : currentPage + 2); index++) {
					listPage.push(
						<Link prefetch={['hover', 'mount']} preserveScroll cacheFor="1m" href={url} key={index} data={{
							[pageName]: index,
						}}>
							<div className="p-2 px-3 md:px-4 bg-green-500 rounded-[28px] justify-center content-center">
								<p className="text:md md:text-xl hover:text-orange-500" style={{
									color: index === currentPage ? 'white' : undefined
								}}>{index}</p>
							</div>
						</Link>
					)
				}
				return listPage;
			})()}

			{pageSize > currentPage + 2 && <Link prefetch={['hover',]} cacheFor="1m" href={url} preserveScroll
				data={{
					[pageName]: currentPage + 5 <= pageSize ? currentPage + 5 : pageSize
				}}>
				<div className="flex p-2 px-3 md:px-4 bg-green-500 rounded-[28px] justify-center content-center items-center">
					<ArrowRightIcon className="h-7 w-5"></ArrowRightIcon>
				</div>
			</Link>}
		</div>
	)
}

export default Paginate;