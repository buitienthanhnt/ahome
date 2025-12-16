import { usePage } from "@inertiajs/react"

const usePageProps = () => {
	const { props } = usePage();
	return props;
}

export default usePageProps;