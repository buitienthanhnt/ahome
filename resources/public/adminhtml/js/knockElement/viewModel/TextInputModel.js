define([
	'require',
	'knockout'
], function(require, ko) {
	'use strict';

	function TextInputModel(params) {
		let self = this;
		/**
		 * params pass data from template component in pageContent.html component.
		 */
		self.attr = params.attr;
		if (params.attr.key) {
			self.attr.name = params.attr.key;
		}
	}
	return TextInputModel;
});