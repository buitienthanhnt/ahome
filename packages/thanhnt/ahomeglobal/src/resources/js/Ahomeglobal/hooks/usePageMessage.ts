import usePageProps from "./usePageProps";

const usePageMessage = () => {
	const { messages } = usePageProps();
	return messages as string;
}

export default usePageMessage;