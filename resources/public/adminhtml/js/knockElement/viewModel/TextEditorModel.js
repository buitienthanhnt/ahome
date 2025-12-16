define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/textEditor.html',
], function (require, ko, textEditorTemplate) {
	'use strict';

	function TextEditorModel(params) {
		// console.log(params);
		let self = this;
		self.attr = { ...params.attr, id: `id-${params.attr.name}` };
		if (params.attr.key) {
			self.attr.name = params.attr.key;
			self.attr.id = `id-${params.attr.key}`;
		}

		/**
		 * this function will be call after render this template of Model.
		 */
		self.koDescendantsComplete = function () {
			tinymce.init({
				path_absolute : "/",
				convert_urls: false,
				selector: `#id-${self.attr.name}`,
				license_key: 'gpl', // gpl for open source, T8LK:... for commercial
				plugins: ["image", "table", "code", "codesample", "media"],
				toolbar1: 'undo redo | fontfamily fontsize styles bold italic underline | alignleft aligncenter alignright alignjustify alignnone | indent outdent | wordcount | lineheight help image media',
				file_picker_callback: function (callback, value, meta) {
					let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
					let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
					tinymce.activeEditor.windowManager.openUrl({
						url: "/adminhtml/file/manager?type=file&editor=tinymce5", // lưu ý phải có giá trị: ?editor=tinymce5 để chạy được vào: onMessage
						title: 'Filemanager',
						width: x * 0.8,
						height: y * 0.8,
						resizable : "yes",
						close_previous : "no",
						onMessage: (dialogApi, details) => {
							callback(details.content);
						},
					});
				},
			});
		}
	}

	ko.components.register('text-editor-com', {
		viewModel: TextEditorModel,
		template: textEditorTemplate
	});
	return TextEditorModel;
});