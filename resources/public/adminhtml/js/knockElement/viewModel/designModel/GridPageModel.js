define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/design/topPage.html',
], function(require, ko, topPageTemplate) {
	'use strict';
	function GridPageModel(params) {
		let self = this;
		self.name = params.attr.name;
		self.type = params.attr.type;

		self.value = ko.computed(function(){
			return JSON.stringify({
				type: self.type,
			})
		})
		
	}

	ko.components.register('gridpage-design-com', {
		viewModel: GridPageModel,
		template: topPageTemplate,
	});
	return GridPageModel;
});