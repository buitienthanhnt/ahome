<?php

namespace Thanhnt\Ahomeglobal\Models\ShareAction;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ImagePathAttrModel
{
	/**
	 * áp dụng cho trường hợp sử dụng tải ảnh qua fileManger, khi đó controller request nhận giá trị đường dẫn là 1 chuỗi
	 * lưu ý hàm này không áp dụng cho trường hợp tải ảnh trực tiếp lên. 
	 * @return Illuminate\Database\Eloquent\Casts\Attribute
	 */
	public function imagePath(): \Illuminate\Database\Eloquent\Casts\Attribute
	{
		return Attribute::make(
			get: function (string|null $value) {
				return $value ? asset($value) : '';
			},
			set: function (string|null $value) {
				// array [
				//   "scheme" => "http"
				//   "host" => "adoc.dev"
				//   "path" => "/storage/files/uploads/261479696_1820281014826477_6400419339212881138_n_084353.jpg"
				// ]
				return $value ? parse_url($value)['path'] : null;
			},
		);
	}
}
