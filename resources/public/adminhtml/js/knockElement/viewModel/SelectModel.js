define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/select.html',
], function(require, ko, selectTemplate) {
	'use strict';

	function SelectModel(params) {
		let self = this;
		self.attr = params.attr;
		if (params.attr.key) {
			self.attr.name = params.attr.key;
		}
		self.options = ko.observableArray(params.attr.options);
		self.selectedOption = ko.observable(JSON.parse(params.attr.value)?.selectValue);

		self.textData = ko.computed(function(){
			return JSON.stringify({
				path: params.attr.path,
				selectValue: self.selectedOption(),
			})
		});
	}

	ko.components.register('select-option-com', {
		viewModel: SelectModel,
		template: selectTemplate
	});

	return SelectModel;
});