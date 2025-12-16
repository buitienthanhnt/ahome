define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/imageSelect.html',
], function (require, ko, imageSelectTemplate) {
	'use strict';

	function ImageSelectModel(params) {
		let self = this;
		self.chooseBtnId = `lfm-${params.attr.key}`;
		self.attr = { ...params.attr, id: `id-${params.attr.key}` };
		if (params.attr.key) {
			self.attr.name = params.attr.key;
		}
		self.imagePath = ko.observable(params.attr.value);

		self.onSelect = function (param, node) {
			/**
			 * var1: this Model
			 * var2: this node of onclick btn.
			 */
			// console.log(param, node);
			var lfm = function (options, cb) {
				var route_prefix = (options && options.prefix) ? options.prefix : filemanager_url_base;
				window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
				window.SetUrl = cb;
			};

			lfm({ type: 'image', }, function (lfmItems,) {
				// console.log(lfmItems);
				lfmItems.forEach(function (lfmItem) {
					self.imagePath(lfmItem.url)
				});
			});
		}
	}

	ko.components.register('image-select-com', {
		viewModel: ImageSelectModel,
		template: imageSelectTemplate
	});

	return ImageSelectModel;
});