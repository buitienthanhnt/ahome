export type AppPage = {
	component: string,
	props: any,
	url: string,
	version: string,
	clearHistory: boolean,
	encryptHistory: boolean,
	rememberedState: any,
};

// {
//     "component": "Ahomeglobal/Screens/Ahome",
//     "props": {
//         "data": 123
//     },
//     "url": "/ahome/home",
//     "version": "",
//     "clearHistory": false,
//     "encryptHistory": false,
//     "rememberedState": {}
// }