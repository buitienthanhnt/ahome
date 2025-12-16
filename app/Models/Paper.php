<?php

namespace App\Models;

use App\Api\BaseApi;
use App\Events\PaperDeleted;
use App\Events\PaperSaved;
use App\Helper\ImageUpload;
use App\Helper\Nan;
use App\Models\Scope\ActiveScopeTrait;
use App\Observers\PaperObserver;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Paper extends Model implements PaperInterface
{
    use HasFactory;
    use SoftDeletes;
    use ImageUpload;
    use ActiveScopeTrait;
    use Nan;

    protected $guarded;
    protected $viewSource = null;
    protected $content = null;

    protected $paperCategory = null;
    protected $categories = null;
    protected $writer;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => PaperSaved::class,
        'deleted' => PaperDeleted::class,
    ];

    public static function boot()
    {
        /**
         * gọi vào parent để gọi đăng ký cho các trait như: ActiveScopeTrait
         * vì hàm này bị ghi đè trong Model Paper nên nếu không gọi lại parent nó sẽ không chạy.
         */
        parent::boot();

        Paper::observe(PaperObserver::class);

        static::updating(function ($instance) {
            Cache::put('api_detail_' . $instance->id, $instance);
        });

        static::deleting(function ($instance) {
            // delete post cache
            Cache::forget('api_detail_' . $instance->id);
        });
    }

    public static function filterPost() {
        $posts = self::query();

        $pipeline = app(Pipeline::class)
            ->send($posts)
            ->through([
                \App\QueryFilters\Active::class,
                \App\QueryFilters\Sort::class
            ])
            ->thenReturn();
        return $pipeline->get();
    }

    /**
     * lấy ảnh đại diện của bài viết.
     */
    public function getImagePath(): string
    {
        return $this->getImageUrl($this->{$this::ATTR_IMAGE_PATH});
    }

    /**
     * lấy liên kết bảng trung gian
     */
    public function toPaperCategory(): HasMany
    {
        return $this->hasMany(PaperCategory::class, Paper::PRIMARY_ALIAS);
    }

    public function joinPaperCategory()
    {
        return $this->belongsToMany(Category::class, "paper_category", "paper_id", "category_id");
    }

    /**
     * lấy danh sách kết quả bảng trung gian.
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getPaperCategories()
    {
        return $this->toPaperCategory;
    }

    function firstCategory(){
        if ($first = $this->toPaperCategory->first()){
            return $first->toCategory->first();
        }
        return null;
    }

    /**
     * lấy danh sách id của category.
     */
    function listIdCategories(): array
    {
        return $this->getPaperCategories()->pluck(CategoryInterface::PRIMARY_ALIAS)->toArray() ?: [];
    }

    /**
     * lấy danh sách category của bài viết.
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getCategories()
    {
        if ($this->categories) {
            return $this->categories;
        }
        return $this->categories = Category::find($this->listIdCategories());
    }

    function joinTags()
    {
        return $this->hasMany(PaperTag::class, PaperTagInterface::ATTR_ENTITY_ID);
    }

    /**
     * lấy các tag của bài viết.
     * tìm 1 khóa chính -> nhiều khóa phụ.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getTags()
    {
        $tags = $this->joinTags->where(PaperTagInterface::ATTR_TYPE, PaperTagInterface::TYPE_PAPER);
        return $tags;
    }

    function joinWriter()
    {
        return $this->belongsTo(Writer::class, PaperInterface::ATTR_WRITER);
    }

    /**
     * lấy tác giả của bài viết.
     * @return Writer
     */
    public function getWriter()
    {
        return $this->joinWriter;
    }

    /**
     * @return string
     */
    public function writerName(): string
    {
        return $this->joinWriter->name ?? '';
    }

    /**
     * @return string
     */
    public function updatedTime(): string
    {
        return $this->getUpdatedAt();
    }

    function joinContent()
    {
        return $this->hasMany(PaperContent::class, PaperInterface::PRIMARY_ALIAS);
    }

    /**
     * lấy danh sách thành phần nội dung của bài viết.
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getContents()
    {
        return $this->joinContent;
    }

    function getTimeline()
    {
        return $this->joinContent->filter(function ($item) {
            return $item[PaperContentInterface::ATTR_TYPE] === PaperContentInterface::TYPE_TIMELINE;
        });
    }

    function getPriceType() {
        
        return $this->joinContent->filter(function ($item) {
            return $item[PaperContentInterface::ATTR_TYPE] === PaperContentInterface::TYPE_PRICE;
        })->first();
    }

    function linkComment()
    {
        return $this->hasMany(Comment::class, PaperInterface::PRIMARY_ALIAS)->where(CommentInterface::ATTR_PARENT_ID, null);
    }

    /**
     * lấy comment con theo paper_id và comment cha.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getComments($parentId = null, int $page = 0, int $limit = 8)
    {
        if ($page === 0 && $limit === 0) {
            return $this->hasMany(Comment::class, PaperInterface::PRIMARY_ALIAS)->where(CommentInterface::ATTR_PARENT_ID, $parentId)->getResults();
        }
        return $this->hasMany(Comment::class, PaperInterface::PRIMARY_ALIAS)->where(CommentInterface::ATTR_PARENT_ID, $parentId)->limit($limit)->offSet($page * $limit)->getResults();
    }

    /**
     * @return LengthAwarePaginator
     */
    function getCommentPaginate($size = 12)
    {
        return $this->linkComment()->paginate($size);
    }

    /**
     * lấy cây đệ quy tuần tự của comment
     * @return Illuminate\Database\Eloquent\Collection
     */
    function getCommentTree($parentId = null, int $page = 0, int $limit = 4)
    {
        $comments = $this->getComments($parentId, $page, $limit);
        if ($comments->count()) {
            foreach ($comments as &$comment) {
                $childrents = $this->getComments($comment->id);
                if ($childrents->count()) {
                    $comment->childrents = $this->getCommentTree($comment->id, $page, $limit);
                } else {
                    $comment->childrents = null;
                }
            }
        }
        return $comments;
    }

    /**
     * lấy tổng số bình luận của bài viết.
     */
    function commentCount(): int
    {
        try {
            return $this->linkComment()->count();
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 0;
    }

    function joinViewSource() {
        return $this->hasOne(ViewSource::class, ViewSourceInterface::ATTR_SOURCE_ID);
    }

    /**
     * lấy trạng thái hoạt động của bài đăng.
     * @return ViewSource
     */
    function viewSource(): ViewSource
    {
        try {
            if ($this->viewSource) {
                return $this->viewSource;
            }
            $viewSource = $this->hasMany(ViewSource::class, ViewSourceInterface::ATTR_SOURCE_ID)->where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)->first();
            $this->viewSource = $viewSource;
            return $viewSource;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return new ViewSource();
    }

    /**
     * lấy số lượt xem
     */
    function viewCount(): int
    {
        $viewSource = $this->joinViewSource;
        return $viewSource->value ?? 0;
    }

    /**
     * lấy số lượt like
     */
    function paperLike(): int
    {
        return $this->joinViewSource ? $this->joinViewSource->like : 0;
    }

    function paperLiked(): bool
    {
        $liked_ids = Session::get(self::LIKED_IDS);
        return $liked_ids && in_array($this->id, $liked_ids) ? true : false;
    }

    function paperHearted(): bool
    {
        $hearted_ids = Session::get(self::HEARTED_IDS);
        return $hearted_ids && in_array($this->id, $hearted_ids) ? true : false;
    }

    /**
     * lấy số lượt thả tim
     */
    function paperHeart(): int
    {
        return $this->joinViewSource ? $this->joinViewSource->heart : 0;
    }

    /**
     * lấy thông tin hoạt động bài viết
     * @return array
     */
    function paperInfo(): array
    {
        return [
            'view_count' => $this->viewCount(),
            'comment_count' => $this->commentCount(),
            'like' => $this->paperLike(),
            'heart' => $this->paperHeart(),
        ];
    }

    /**
     * @return string
     */
    function getUrl(): string
    {
        return '';
        return route(
            'front_paper_detail',
            [
                'alias' => $this->{PaperInterface::ATTR_URL_ALIAS},
                'paper_id' => $this->id
            ]
        );
    }

    /**
     * @param array $ids
     * @return mixed
     */
    function getPaperByIds(array $ids = [])
    {
        return $this->find($ids);
    }

    /**
     * @return mixed
     */
    function getRelatedItems()
    {
        $paperIds = [];
        $categories = $this->getCategories();
        foreach ($categories as $category) {
            $paperIds = array_merge($category->listIdPapers(), $paperIds);
        }
        return $this->getPaperByIds(!empty($paperIds) ? array_slice(array_unique($paperIds), 0, 6) : []);
    }

    // https://topdev.vn/blog/carbon-laravel/
    function getUpdatedAt(): string
    {
        // return date('M d, Y', strtotime($this->updated_at));
        // return '';
        // ======================= new
        Carbon::setLocale('vi');
        $now = Carbon::now();
        $date = Carbon::rawCreateFromFormat('Y-m-d H:i:s', $this->updated_at);
        $timelineDay = $date->diffInDays($now);
        if ($timelineDay >= 2) {
            return $date->toDateString();
        } elseif ($timelineDay == 1) {
            return 'hôm qua';
        }
        $timelineHours = $date->diffInHours($now);
        return $timelineHours > 8 ? 'hôm nay' : $date->diffForHumans(Carbon::now()); //1 giờ trước
    }

    function getCreatedAt(): string
    {
        Carbon::setLocale('vi');
        $now = Carbon::now();
        $date = Carbon::rawCreateFromFormat('Y-m-d H:i:s', $this->created_at);
        $timelineDay = $date->diffInDays($now);
        if ($timelineDay >= 2) {
            return $date->toDateString();
        } elseif ($timelineDay == 1) {
            return 'hôm qua';
        }
        $timelineHours = $date->diffInHours($now);
        return $timelineHours > 8 ? 'hôm nay' : $date->diffForHumans(Carbon::now()); //1 giờ trước
    }
}
