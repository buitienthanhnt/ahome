<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperTag extends Model implements PaperTagInterface
{
    use HasFactory;
    protected $table = self::TABLE_NAME;

    /**
     * @param string $tag
     * @return int[]
     */
    public static function getPaperIds($tag)
    {
        return self::where(self::ATTR_TYPE, self::TYPE_PAPER)->where(self::ATTR_VALUE, $tag)->pluck(self::ATTR_ENTITY_ID)->toArray() ?? [];
    }

    /**
     * @param string $tag
     * @return mixed
     */
    public static function getPaperByTags(string $tag){
        return Paper::find(self::getPaperIds($tag));
    }

    public function getUrl(): string{
        return route('front_tag_view', ['value' => $this->{self::ATTR_VALUE}]);
    }
}
