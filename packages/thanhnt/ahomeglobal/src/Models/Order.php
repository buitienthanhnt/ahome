<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Database\Factories\OrderFactory;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Observers\OrderObserver;

#[UseFactory(OrderFactory::class)]
#[ObservedBy([OrderObserver::class])]
class Order extends Model implements OrderInterface
{
    use HasFactory;
    use SoftDeletes;

    protected $table = self::TABLE_NAME;

    /**
     * khai báo chuyển đổi kiểu dữ liệu
     */
    protected $casts = [
        self::SELECTED_TIME => 'array',  // Casts the 'SELECTED_TIME' column to an array
    ];

    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * link the order to room
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, self::ROOM_ID, Room::ID);
    }

    /**
     * link the order to home
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class, self::HOME_ID, Home::ID);
    }
}
