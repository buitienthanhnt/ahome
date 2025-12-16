<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;

class OrderTime extends Model implements OrderTimeInterface
{
    use SoftDeletes;

    /**
     * khai báo chuyển đổi kiểu dữ liệu
     */
    protected $casts = [
        self::ORDER_IDS => 'array',
        self::ROOM_IDS => 'array',  // Casts the 'ROOM_IDS' column to an array
    ];

    protected $fillable = self::FILLED_FILEDS;
    protected $hidden = self::HIDDEN_FIELDS;

    //
}
