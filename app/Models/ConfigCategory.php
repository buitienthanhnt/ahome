<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    const TABLE_NAME = 'config_categories';

    const ATTR_PATH = 'path';
    const ATTR_VALUE = 'value';
    const ATTR_DESCRIPTION = 'description';

    const TOP_CATEGORY = "top_category";
    const CENTER_CATEGORY = "center_category";
    use SoftDeletes;
}
