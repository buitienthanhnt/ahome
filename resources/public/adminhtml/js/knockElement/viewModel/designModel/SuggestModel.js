define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/design/topPage.html',
], function(require, ko, topPageTemplate) {
	'use strict';
	function SuggestModel(params) {
		let self = this;
		self.name = params.attr.name;
		self.type = params.attr.type;

		self.value = ko.computed(function(){
			return JSON.stringify({
				type: self.type,
			})
		})
		
	}

	ko.components.register('suggest-design-com', {
		viewModel: SuggestModel,
		template: topPageTemplate,
	});
	return SuggestModel;
});