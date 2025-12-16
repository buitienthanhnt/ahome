<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;

class Attr extends Model implements AttrInterface
{
    protected $table = self::TABLE_NAME;

    use SoftDeletes;
    protected $fillable = self::FILLED_FIELDS;
    
}