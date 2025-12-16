import { Link, usePage } from "@inertiajs/react";

const Ahome = (params) => {
	const props = usePage();

	console.log('====================================');
	console.log(props, params);
	console.log('====================================');

	return (
		<div className="flex min-h-screen rounded-lg p-4 bg-blue-gray-200 justify-center items-center">
			<h3>demo for home page of ahome global route package!!!</h3>
			<Link href={'/'} className="text-green-500">Go to home</Link>
		</div>
	);
}

export default Ahome;