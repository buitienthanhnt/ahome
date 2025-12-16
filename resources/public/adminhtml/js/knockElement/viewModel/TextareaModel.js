define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/textarea.html',
], function(require, ko, textareatemplate) {
	'use strict';

	function Textarea(params) {
		let self = this;
		/**
		 * params pass data from template component in pageContent.html component.
		 */
		self.attr = params.attr;
		if (params.attr.key) {
			self.attr.name = params.attr.key;
		}
	}

	ko.components.register('text-area-com', {
		viewModel: Textarea,
		template: textareatemplate
	});

	return Textarea;
});