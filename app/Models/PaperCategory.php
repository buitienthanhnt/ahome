<?php

namespace App\Models;

use App\Models\PaperCategoryInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Paper;

class PaperCategory extends Model implements PaperCategoryInterface
{
    use HasFactory;
    protected $table = SELF::TABLE_NAME;

    public function toCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, CategoryInterface::PRIMARY_ALIAS);
    }

    public function toProduct(): BelongsTo
    {
        return $this->belongsTo(Paper::class, PaperInterface::PRIMARY_ALIAS);
    }
}
