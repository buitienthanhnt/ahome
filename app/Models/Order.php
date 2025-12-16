<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements OrderInterface
{
    use HasFactory;
    protected $table = self::TABLE_NAME;
    protected $guarded = [];

    function orderItems(): mixed
    {
        return $this->hasMany(OrderItem::class, "order_id")->getResults()->makeHidden(['url']);
    }

    function orderAddress(): mixed
    {
        return $this->hasMany(OrderAddress::class, "order_id")->getResults();
    }
}
