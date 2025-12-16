define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/textField',
], function (require, ko, textFieldTemplate) {
	'use strict';
	ko.components.register('my-component', {
		viewModel: { require: 'some/module/name' },
		template: textFieldTemplate
	});
});