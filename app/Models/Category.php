<?php

namespace App\Models;

use App\Api\BaseApi;
use App\Helper\ImageUpload;
use App\Helper\Nan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Category extends Model implements CategoryInterface
{
    use HasFactory;
    use ImageUpload;
    use SoftDeletes;
    use Nan;

    // public $timestamps = false;
    protected $guarded = [];
    protected $_selected = array();
    protected $select_key = "*";
    protected $current_type = "default";

    protected $paperCategory = null;

    public function newQuery()
    {
        return parent::newQuery()->where('active', '=', 1);
    }

    /**
     * lấy liên kết bảng trung gian
     */
    protected function toPaperCategory(): HasMany
    {
        return $this->hasMany(PaperCategory::class, CategoryInterface::PRIMARY_ALIAS);
    }

    /**
     * lấy danh sách id của paper.
     * @return int[]
     */
    public function listIdPapers()
    {
        return $this->getPaperCategories->pluck(PaperInterface::PRIMARY_ALIAS)->toArray() ?: [];
    }

    /**
     * lấy danh sách các bài viết của category này.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getPapers()
    {
        return Paper::find($this->listIdPapers());
    }

    /**
     * @return HasMany
     */
    public function getChildrent()
    {
        return $this->hasMany($this, CategoryInterface::ATTR_PARENT_ID);
    }

    /**
     * lấy danh sách <option> của category dạng html
     * hàm này mặc định là lấy type='default'(category)
     * @return string
     */
    public function category_tree_option($category = null)
    {
        $parent_category = '<option value="0">Root category</option>';
        $list_catergory = $this->all()->where(CategoryInterface::ATTR_PARENT_ID, 0)->where(CategoryInterface::ATTR_TYPE, $this->current_type);
        if ($list_catergory->count()) {
            if ($category) {
                $parent_category .= $this->category_tree($list_catergory, "", $category->parent_id);
            } else {
                $parent_category .= $this->category_tree($list_catergory);
            }
        }
        return $parent_category;
    }

    /**
     * @param Category $category
     * @param string   $begin
     * @param int      $selected
     * @return string
     */
    protected function category_tree($catergory, $begin = "", $selected = null)
    {
        $html = "";
        $prefix = '___';
        foreach ($catergory as $cate) {
            $html .= '<option value="' . $cate->id . '" ' . ($this->_selected ? (in_array($cate->id, $this->_selected) ? "selected " : "") : ($selected === $cate->id ? "selected " : "")) . '>' . $begin . $cate->name . '</option>';
            if ($list_catergory = $cate->getChildrent) {
                $_be = $begin;
                $begin .= $prefix;
                $html .= $this->category_tree($list_catergory, $begin, $selected);
                $begin = $_be;
            } else {
                continue;
            }
        }
        return $html;
    }

    /**
     * lấy danh sách timeLine dạng html
     * @param int[] $selected
     * @return string
     */
    public static function timelineOptionHtml($selected = [])
    {
        $time_lines = Category::all()->where(CategoryInterface::ATTR_TYPE, CategoryInterface::TYPE_TIME_LINE);
        $html = '';
        foreach ($time_lines as $value) {
            $html .= '<option value="' . $value->id . '" ' . (in_array($value->id, $selected) ? 'selected ' : '') . '>' . $value->name . '</option>';
        }
        return $html;
    }

    /**
     * lấy danh sách kết quả bảng trung gian.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getPaperCategories()
    {
        return $this->toPaperCategory();
    }

    /**
     * lấy danh sách bài viết có phân trang
     * @param int   $limit    (số bài 1 trang)
     * @param int offset (trang hiện tại)
     * @param array $order_by (sắp xếp theo)
     */
    public function getPaperPaginate($limit = 12)
    {
        return $this->getPaperByCategory($limit);
    }

    /**
     * lấy danh sách paper của category này
     * trả về 1 LengthAwarePaginator phân trang.
     * @return LengthAwarePaginator
     */
    public function getPaperByCategory(int $limit = 12)
    {
        $listPaperIds = $this->listIdPapers();
        if ($this->{self::ATTR_TYPE} === self::TYPE_TIME_LINE) {
            $timeLinePaperIds = DB::table(PaperContentInterface::TABLE_NAME)->where([
                [PaperContentInterface::ATTR_TYPE, '=', PaperContentInterface::TYPE_TIMELINE],
                [PaperContentInterface::ATTR_DEPEND_VALUE, '=', $this->id]
            ])->pluck("paper_id")->toArray() ?? [];
            $listPaperIds = array_merge($timeLinePaperIds);
        }
        return Paper::whereIn('id', $listPaperIds)->with("joinViewSource")->with("joinWriter")->paginate($limit);
    }

    /**
     * lấy danh sách category tree dạng data
     * @return array
     */
    function getCategoryTree($root = false)
    {
        if ($root) {
            $currentCategory["name"] = "";
            $currentCategory["id"] = 0;
        } else {
            $currentCategory["name"] = $this->name;
            $currentCategory["id"] = $this->id;
            $currentCategory["alias"] = $this->{self::ATTR_URL_ALIAS};
        }
        $childrens = Category::where(CategoryInterface::ATTR_PARENT_ID, $root ? 0 : $this->id)->get();
        if (count($childrens)) {
            $items = null;
            foreach ($childrens as $children) {
                if (!$children->active) {
                    continue;
                }
                $category = $children->getCategoryTree();
                $items[] = $category;
            }
            $currentCategory["items"] = $items;
        } else {
            $currentCategory["items"] = null;
        }
        return $currentCategory;
    }

    public function getcategoryTreeData(){
        if (Cache::has("categoryTreeData")){
            return Cache::get("categoryTreeData");
        }
        $categoryTreeData = $this->getCategoryTree(true);
        Cache::add("categoryTreeData", $categoryTreeData);
        return $categoryTreeData;
    }

    /**
     * @return string
     */
    function getUrl(): string
    {
        return '';
        return route('front_category', ['category' => $this->{CategoryInterface::ATTR_URL_ALIAS}]);
    }

    function getImagepath()
    {
        return $this->getImageUrl($this->{$this::ATTR_IMAGE_PATH});
    }

    // ===================================================================

    public function setSelected($_selected = [])
    {
        $this->_selected = $_selected;
        return $this;
    }

    public function setSelectKey($key = [])
    {
        $this->select_key = $key;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    function setCurrentType(string $type = 'default')
    {
        $this->current_type = $type;
        return $this;
    }
}
