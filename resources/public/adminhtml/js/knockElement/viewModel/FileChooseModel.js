define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/fileChoose.html',
], function(require, ko, fileChooseTemplate) {
	'use strict';
	function FileChoose(params) {
		let self = this;
		self.attr = params.attr;
		if (params.attr.key) {
			self.attr.name = params.attr.key;
		}
		self.imagePath = ko.observable(params.attr.value);

		self.onchange = function (data, event) { 
			var reader = new FileReader();
			/**
			 * gán hàm xử lý: onload sau khi chạy: readAsDataURL
			 * e.target.result có giá trị là base-64 text-image
			 */
            reader.onload = function(e) {
               self.imagePath(e.target.result)
            };
			/**
			 * get selected file
			 */
            reader.readAsDataURL(event.target.files[0]);
		}
	}

	ko.components.register('file-choose-com', {
		viewModel: FileChoose,
		template: fileChooseTemplate
	});
	return FileChoose;
});