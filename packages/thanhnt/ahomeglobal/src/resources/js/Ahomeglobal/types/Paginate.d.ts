export interface PaginateProp {
	pageSize: number;
	currentPage: number;
	url?: string;
	pageName?: string;
}

type DataType = {
	[key: string]: any
}

type PaginateLink = {
	url: string;
	label: string;
	active: boolean;
}

export interface PagePaginate {
	current_page: number;
	data: DataType[];
	from: number;
	last_page: number;
	total: number;
	path: string;
	first_page_url?: string;
	last_page_url?: string;
	links?: PaginateLink[];
	next_page_url: string | null;
	per_page?: number;
	prev_page_url?: string;
	to?: number;
}