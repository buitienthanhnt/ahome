define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/design/topPage.html',
], function(require, ko, topPageTemplate) {
	'use strict';
	function VideosModel(params) {
		let self = this;
		self.name = params.attr.name;
		self.type = params.attr.type;

		self.value = ko.computed(function(){
			return JSON.stringify({
				type: self.type,
			})
		})
		
	}

	ko.components.register('videos-design-com', {
		viewModel: VideosModel,
		template: topPageTemplate,
	});
	return VideosModel;
});