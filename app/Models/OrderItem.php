<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model implements OrderItemInterface
{
    use HasFactory;
    protected $table = self::TABLE_NAME;
    protected $guarded = [];
}
