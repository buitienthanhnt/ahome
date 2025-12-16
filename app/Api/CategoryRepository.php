<?php
namespace App\Api;

use App\Enums\CacheStorage;
use App\Helper\HelperFunction;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryRepository{
    protected $category;

    protected $helperFunction;

    function __construct(
        Category $category,
        HelperFunction $helperFunction
    )
    {
        $this->category = $category;
        $this->helperFunction = $helperFunction;
    }

    function getById(int $category_id){
        $cache_key = CacheStorage::CATEGORY_DETAIL_MODEL.$category_id;
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }
        /**
         * @var Category $category
         */
        $category = $this->category->find($category_id);
        Cache::put($cache_key, $category);
        return $category;
    }
}
