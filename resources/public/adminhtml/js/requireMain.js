requirejs.config({
	// baseUrl: '/laravel1/public/assets/all/requireJs/data', //: windown
	baseUrl: '/source/adminhtml/js', //: ubuntu phải có dấu: / ở đầu để xác định đường dẫn chính xác từ gốc domain.
	paths: {
		app: 'knockElement/app',
		viewModels: 'knockElement/viewModel',
		viewTemplates: 'knockElement/viewTemplate',
		components: 'components'
	},
	shim: {
		'underscore': {
			exports: '_'
		},
		'jquery': {
			exports: '$'
		},
		'knockout': {
			exports: 'ko'
		},
	},
});