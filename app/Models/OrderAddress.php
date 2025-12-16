<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model implements OrderAddressInterface
{
    use HasFactory;
    protected $table = self::TABLE_NAME;
    protected $guarded = [];
}
