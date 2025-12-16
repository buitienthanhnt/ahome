<?php

namespace Thanhnt\Ahomeglobal\Database\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RoomResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [];
		return parent::toArray($request);
	}
}
