<?php

namespace App\Models\Types;

interface FormInterface{

	const TYPE_TEXT = 'text';
	const TYPE_TEXTAREA = 'textarea';
	const TYPE_TEXTEDITOR = 'textEditor';
	const TYPE_EMAIL = 'email';
	const TYPE_PASSWORD = 'password';
	const TYPE_NUMBER = 'number';
	const TYPE_BOOL = 'boolean';
	const TYPE_SELECT = 'select';
	const TYPE_MULTISELECT = 'multiselect';
	const TYPE_DROPDOWN = 'dropdown';
	const TYPE_CHECKBOX = 'checkbox';
	const TYPE_RADIO = 'radio';
	const TYPE_COLOR = 'color';
	const TYPE_FILE = 'file';
	const TYPE_MULTIFILE = 'multifile';
	const TYPE_PHONE = 'tel';
	const TYPE_DATE = 'date';
	const TYPE_IMAGE_CHOOSE = 'imageChoose';
	const TYPE_TIMELINE = 'timeline';
	const CAROUSEL = 'carousel';
}
