define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/design/banner.html'
], function(require, ko, bannerTemplate) {
	'use strict';
	function BannerModel(params) {
		// console.log(params, '==========>>>');

		let self = this;
		self.name = params.attr.name;
		self.type = params.attr.type;
		self.title = ko.observable(params?.attr?.data?.title);
		self.imagePath = ko.observable(params?.attr?.data?.imagePath);
		self.page = ko.observable(params?.attr?.data?.page);

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

		self.onSelectPage = function(param, node){

		}

		self.value = ko.computed(function (params) {
			let data = {
				title: self.title(),
				imagePath: self.imagePath(),
				page: self.page(),
				type: self.type,

			};
			return JSON.stringify(data);
		})
	}

	ko.components.register('banner-design-com', {
		viewModel: BannerModel,
		template: bannerTemplate
	});
	return BannerModel;
});