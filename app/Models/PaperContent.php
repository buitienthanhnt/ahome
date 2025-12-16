<?php

namespace App\Models;

use App\Helper\Nan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaperContent extends Model implements PaperContentInterface
{
    use Nan;
    use HasFactory;
    protected $guarded = [];

    /**
     * lấy ảnh đại diện của bài viết.
     * @return string|null
     */
    public function getImagePath()
    {
        if ($this->{self::ATTR_TYPE} === self::TYPE_IMAGE) {
            return $this->getImageUrl($this->{$this::ATTR_VALUE});
        }
        return null;
    }

    function toPaper(): BelongsTo
    {
        return $this->belongsTo(Paper::class, PaperInterface::PRIMARY_ALIAS);
    }

    function getPaper()
    {
        return $this->toPaper;
    }

    /**
     * @return Builder
     */
    function getByType(array $type = [])
    {
        return $this->whereIn(self::ATTR_TYPE, $type);
    }

    /**
     * lấy giá(nếu có) đã đổi ra đơn vị vnđ.
     * @param false $format
     * @return float|int|null
     */
    function getPrice($format = false)
    {
        try {
            $price = $this->{static::ATTR_VALUE};
            if ($price) {
                return $format ? number_format($price * 1000) : $price * 1000;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return null;
    }
}
