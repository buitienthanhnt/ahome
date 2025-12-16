<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewSource extends Model implements ViewSourceInterface
{
    use HasFactory;
    protected $table = self::TABLE_NAME;
    protected $guarded = [];
}
