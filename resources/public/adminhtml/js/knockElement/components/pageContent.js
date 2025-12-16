define([
	'require',
	'knockout',
	'knockElement/viewModel/pageContentModel',
	'text!knockElement/viewTemplate/pageContent.html',
	'knockElement/viewModel/TextEditorModel',
	'knockElement/viewModel/FileChooseModel',
	'knockElement/viewModel/TextareaModel',
	'knockElement/viewModel/SelectModel',
	'knockElement/viewModel/ImageSelectModel',
	'knockElement/viewModel/TimelineModel', 
	'knockElement/viewModel/CarouselModel', 
	'knockElement/components/TextInput', 
], function(require, ko, pageContentModel, pageContentTemplate, TextEditorModel, FileChooseModel, TextareaModel, SelectModel, ImageSelectModel, TimelineModel, CarouselModel, TextInput,) {
	'use strict';
	// console.log('===>', pageContentFields);
	ko.components.register('pageContentId', {
		viewModel: pageContentModel.bind(pageContentModel, { // pass init values for the function define
			defaultSupportFields: defaultSupportFields,
			inputFields: pageContentFields,
			customFields: customFields,
		}),
		template: pageContentTemplate
	});

	ko.applyBindings();
});