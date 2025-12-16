define([
	'require',
	'knockout',
	'knockElement/viewModel/PageDesignModel',
	'text!knockElement/viewTemplate/pageDesign.html',
	'knockElement/viewModel/designModel/BannerModel',
	'knockElement/viewModel/designModel/TopPageModel',
	'knockElement/viewModel/designModel/TopCommentModel',
	'knockElement/viewModel/designModel/CenterCategoryModel',
	'knockElement/viewModel/designModel/TimeLineModel',
	'knockElement/viewModel/designModel/PageRandomModel',
	'knockElement/viewModel/designModel/LatesPageModel',
	'knockElement/viewModel/designModel/VideosModel', // GridPageModel
	'knockElement/viewModel/designModel/GridPageModel', // ListVerticalModel
	'knockElement/viewModel/designModel/ListVerticalModel', // ListHorizonModel
	'knockElement/viewModel/designModel/ListHorizonModel', // ChartModel
	'knockElement/viewModel/designModel/ChartModel', // SuggestModel
	'knockElement/viewModel/designModel/SuggestModel',
], function(require, ko, PageDesignModel, pageDesignTemplate, 
	BannerModel, TopPageModel, TopCommentModel, CenterCategoryModel, 
	TimeLineModel, PageRandomModel, LatesPageModel, VideosModel, GridPageModel,
	ListVerticalModel, ListHorizonModel, ChartModel, SuggestModel, 
	) {
	'use strict';
	// console.log('===>', pageContentFields);

	ko.components.register('pageSetupId', {
		viewModel: PageDesignModel.bind(PageDesignModel, { // pass init values for the function define
			// defaultSupportFields: defaultSupportFields,
			// inputFields: pageContentFields,
			// customFields: customFields,
		}),
		template: pageDesignTemplate,
	});

	ko.applyBindings();
});