import React, { FunctionComponent } from "react";

type Props = {
	url: string;
	style?: string;
	attributes?: any;
}

{/* embled google map: https://learn.showit.com/en/articles/5217010-google-maps-embed */ }
const demoLocation = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29803.393747998427!2d105.7730197906494!3d20.975625585577415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab940344fcd5%3A0x9a83404ac3731068!2zVmnhu4duIEtp4buDbSBTw6F0IE5ow6JuIETDom4gVGjDoG5oIFBo4buRIEjDoCBO4buZaQ!5e0!3m2!1svi!2s!4v1765343379188!5m2!1svi!2s';

const Location: FunctionComponent<Props> = ({
	url, style = 'w-[600px] h-[450px] border p-1', attributes
}) => {
	return (
		<div className="flex justify-end items-center">
			<iframe src={url}
				className={style} loading="lazy" {...attributes}></iframe>
		</div>
	)
}
export default Location;
// export default React.memo(Location);