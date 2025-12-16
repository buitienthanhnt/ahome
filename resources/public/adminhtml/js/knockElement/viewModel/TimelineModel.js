define([
	'require',
	'knockout',
	'text!knockElement/viewTemplate/timeline.html',
], function (require, ko, timelineTemplate) {
	'use strict';

	function TimelineModel(params) {
		let self = this;
		self.name = params.attr?.name ||  params.attr?.key || null;
		self.timelines = ko.observableArray(params.attr?.options || []);

		self.selected = ko.observable(null);
		self.time = ko.observable(null);
		self.id = `id-${self.name}`;

		self.dataText = ko.computed(function () {
			if (!self.selected() || !self.time()) {
				return '';
			}
			return JSON.stringify({
				typeValue: self.selected(),
				typeTitle: self.timelines().find((i) => i.value === self.selected()).label,
				timeValue: self.time(),
				path: params.attr.path,
			});
		})

		/**
		 * this function will be call after render this template of Model.
		 */
		self.koDescendantsComplete = function () {
			/**
			 * init for set default data field load from server.
			 */
			if (params.attr.value) {
				const data = JSON.parse(params.attr.value);
				// self.timelines([
				// 	{ label: data.typeTitle || 'none label', value: data.typeValue }
				// ]);
				self.selected(data.typeValue);
				self.time(data.timeValue);
			}
			// $('#timepicker').timepicker();   https://gijgo.com/timepicker
			$(`#${self.id}`).datetimepicker({
				datepicker: {
					showOtherMonths: true,
					calendarWeeks: true,
					todayHighlight: true
				},
				footer: true,
				modal: true,
				header: true,
				value: self.time(),
				format: 'yyyy-mm-dd HH:MM:ss',
			});
		}
	}

	ko.components.register('timeline-com', {
		viewModel: TimelineModel,
		template: timelineTemplate
	});

	return TimelineModel;
});