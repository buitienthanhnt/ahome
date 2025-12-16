<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model implements NotificationInterface
{
    use HasFactory;
    use SoftDeletes;
    protected $table = self::TABLE_NAME;
    protected $guarded = [];
}
