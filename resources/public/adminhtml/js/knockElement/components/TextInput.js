define([
	'require',
	'knockout',
	'knockElement/viewModel/TextInputModel',
	'text!knockElement/viewTemplate/textInput.html',
], function (require, ko, TextInputModel, TextInputTemplate) {
	'use strict';
	ko.components.register('text-input-com', {
		viewModel: TextInputModel,
		template: TextInputTemplate
	});
});