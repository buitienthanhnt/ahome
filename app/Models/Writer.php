<?php
namespace App\Models;

use App\Api\BaseApi;
use App\Helper\Nan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Writer extends Model implements WriterInterface
{
    use HasFactory;
    use SoftDeletes;
    use Nan;
    protected $guarded = [];

    /**
     * @return HasMany
     */
    function getPapers(): HasMany {
        return $this->hasMany(Paper::class, PaperInterface::ATTR_WRITER);
    }

    /**
     * @return mixed
     */
    function getPaperByWriter(){
        return $this->getPapers()->getResults();
    }

    function getPaperWithPaginate($limit = 12){
        return $this->getPapers()->paginate($limit);
    }

    public static function getUrl()
    {
        return route('');
    }

    /**
     * lấy ảnh đại diện của bài viết.
     */
    public function getImagePath(): string
    {
        return $this->getImageUrl($this->{$this::ATTR_IMAGE_PATH});
    }
}
