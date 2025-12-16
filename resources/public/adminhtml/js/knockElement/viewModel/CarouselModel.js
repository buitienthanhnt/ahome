define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/carousel.html',
], function(require, ko, carouselTemplate) {
	'use strict';
	
	function CarouselModel(params) {
		let self = this;
		self.name = params.attr.name;
		self.title = ko.observable('');
		self.imagePath = ko.observable('');
		self.items = ko.observableArray([]);
		self.textData = ko.computed(function(){
			if (!self.items().length) {
				return null;
			}
			return JSON.stringify(self.items());
		});

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
				lfmItems.forEach(function (lfmItem) { // lfmItems: is an array.
					self.imagePath(lfmItem.url);
				});
			});
		}

		self.clear = function(){
			self.title('');
			self.imagePath('');
		}

		self.onSave = function(thisFunction, node){
			self.items.push({title: self.title(), imagePath: self.imagePath()});
			self.clear();
		}

		self.onRemove = function(input){
			// console.log(input); //  input: is params bind in function define template.
			self.items.splice(input.index, 1);
		}

		/**
		 * this function will be call after render this template of Model.
		 */
		self.koDescendantsComplete = function () {
			/**
			 * init for set default data field load from server.
			 */
			if (params.attr.value) {
				const data = JSON.parse(params.attr.value);
				self.items(data);
			}
		}
	}

	ko.components.register('carousel-com', {
		viewModel: CarouselModel,
		template: carouselTemplate
	});

	return CarouselModel;
});