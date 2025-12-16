define([
	'require',
	'knockout',
	'underscore'
], function (require, ko, _) {
	'use strict';

	/**
	 * format for input form field type of timeline.
	 * @param {*} params 
	 * @returns array
	 */
	function formatFields(params) {
		return params.inputFields.map((field) => {
			switch (field.type) {
				case 'timeline':
				case 'select':
					return {
						...field,
						path: JSON.parse(field.value).path,
						options: params.customFields.find((customField) => {
							return (customField.type === field.type) && (customField.path === JSON.parse(field.value).path)
						}).options || [],
					};
				default:
					return field;
			}
		})
	}

	function PageContent(params) {

		var self = this;
		self.fields = ko.observableArray(formatFields(params));
		self.customFields = params.customFields;
		self.types = params.defaultSupportFields;

		self.fieldKeys = ko.computed(function () {
			return self.fields().map(function (item) {
				return item.name ?? item.key;
			}).join('|');
		});

		self.addField = function (field) {
			let key = field + '-' + self.fields().length;
			self.fields.push({ name: key, value: null, type: field })
		}

		self.addCustomField = function (field) {
			// console.log(field);
			let key = field.type + '-' + self.fields().length;
			self.fields.push({ name: key, value: null, ...field })
		}

		self.onRemove = function (field) {
			/**
			 * filter for list fields without of the field
			 */
			let newFields = _.filter(self.fields(), function (_field) {
				return _field.name !== field.name;
			});
			self.fields(newFields);
		}

		self.onUp = function (field) {
			let index = _.findIndex(self.fields(), field);
			if (index === 0) {
				return;
			}
			let arrayWithout = _.filter(self.fields(), function (item) {
				return item.name !== field.name;
			});
			let newFields = [
				...arrayWithout.slice(0, index - 1),
				field,
				...arrayWithout.slice(index - 1)
			];
			self.fields(newFields);
		}

		self.onDown = function (field) {
			let index = _.findIndex(self.fields(), field);
			if (index === self.fields.length - 1) {
				return;
			}
			let arrayWithout = _.filter(self.fields(), function (item) {
				return item.name !== field.name;
			});
			let newFields = [
				...arrayWithout.slice(0, index + 1),
				field,
				...arrayWithout.slice(index + 1)
			];
			self.fields(newFields);
		}

		self.logField = function () {
			console.log(self.fields());
		}

		/**
		 * this function will be call after render this template of Model.
		 */
		self.koDescendantsComplete = function () {
			// $(function () {
			// 	$("#sortable").sortable();
			// });
		}
	}

	return PageContent;
});