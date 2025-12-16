import { Alert } from "@material-tailwind/react";
import { FunctionComponent, useEffect, useState } from "react";

interface FlashMessageInterface {
	message: string;
}
const FlashMessage: FunctionComponent<FlashMessageInterface> = ({ message }) => {
	const [show, setShow] = useState<boolean>(true);

	useEffect(() => {
		const autoHide = setTimeout(() => {
			show && setShow(false);
		}, 2000);

		return () => clearTimeout('autoHide');
	}, [show])

	if (!show || !message) {
		return;
	}

	return message && <Alert color="green" variant="gradient">{message}</Alert>
}

export default FlashMessage;