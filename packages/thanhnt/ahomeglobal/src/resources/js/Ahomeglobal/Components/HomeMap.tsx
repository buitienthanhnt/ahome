import { APIProvider, Map, Marker } from '@vis.gl/react-google-maps';

// https://visgl.github.io/react-google-maps/docs/api-reference/components/marker
const HomeMap = () => (
	<APIProvider apiKey={''}>
		<Map
			style={{ width: '100%', height: '460px' }}
			defaultCenter={{ lat: 53.54992, lng: 10.00678 }}
			defaultZoom={12}
			gestureHandling='greedy'
			disableDefaultUI
			// zoom={12} 
			// center={{lat: 53.54992, lng: 10.00678}}
		>
			<Marker position={{lat: 53.54992, lng: 10.00678}} />
			<Marker position={{lat: 53.64992, lng: 10.10678}} label={'nam tan'} onClick={()=> {console.log(234);
			}} />
		</Map>
	</APIProvider>
);

export default HomeMap;