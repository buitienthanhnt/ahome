define([
	'require',
	'knockout',
	'underscore',
], function(require, ko, _) {
	'use strict';
	
	function PageDesignModel(params) {
		let self = this;
		self.elements = [
			{type: 'banner', label: 'banner'}, //
			{type: 'topPage', label: 'top pages'}, //
			{type: 'topComment', label: 'bình luận chính'}, //
			{type: 'pageRandom', label: 'đề xuất ngẫu nhiên'}, //
			{type: 'timeLine', label: 'dòng thời gian'}, //
			{type: 'centerCategory', label: 'danh mục trung tâm'}, //
			{type: 'latestPage', label: 'loạt bài viết mới nhất'}, //
			{type: 'videos', label: 'videos'}, //
			{type: 'suggest', label: 'danh sách đề xuất'}, //
			{type: 'gridPage', label: 'bố cục bài viết dạng lưới'}, //
			{type: 'listVertical', label: 'danh sách phẳng'},  //
			{type: 'listHorizon', label: 'danh sách cuộn ngang'}, //
			{type: 'chart', label: 'biểu đồ '}, //
		];

		self.chooseItems = ko.observableArray([]);

		self.addItem = function (params, node) {
			let count = self.chooseItems().filter(item => item.type === params.type).length ;
			// console.log(params, node, `${params.type}.${count}`);
			self.chooseItems.push({...params, name: `${params.type}.${count}`});
		}

		self.removeItem = function(params){
			/**
			 * filter for list fields without of the field
			 */
			let newFields = _.filter(self.chooseItems(), function (_field) {
				return _field.name !== params.name;
			});
			self.chooseItems(newFields);
		}

		self.onUp = function (params) {
			
		}

		self.onDown = function (params) {
			
		}

		self.onSave = function(){

		}
	}

	return PageDesignModel;
});