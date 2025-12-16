<?php

namespace App\Api;

use App\Api\Data\Category\CategoryItemInterface;
use App\Api\Convert\ConvertCategory;
use App\Api\Convert\ConvertPaper;
use App\Enums\CacheStorage;
use App\Helper\HelperFunction;
use App\Models\Category;
use App\Models\CategoryInterface;
use App\Models\ConfigCategory;
use App\Services\FirebaseService;
use Thanhnt\Nan\Helper\CacheManager;
use Thanhnt\Nan\Helper\LogTha;

final class CategoryApi extends BaseApi
{
    protected $category;
    protected $categoryRepository;
    protected $convertCategory;
    protected $convertPaper;
    protected $helperFunction;
    protected $cacheManager;

    function __construct(
        HelperFunction $helperFunction,
        FirebaseService $firebaseService,
        Category $category,
        CategoryRepository $categoryRepository,
        ConvertCategory $convertCategory,
        ConvertPaper $convertPaper,
        LogTha $logTha,
        CacheManager $cacheManager
    )
    {
        $this->category = $category;
        $this->categoryRepository = $categoryRepository;
        $this->convertCategory = $convertCategory;
        $this->convertPaper = $convertPaper;
        $this->helperFunction = $helperFunction;
        $this->cacheManager = $cacheManager;
        parent::__construct($firebaseService, $logTha);
    }

    function getCategoryById(int $category_id, $with_children = true)
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::CATEGORY_DETAIL_API . $category_id,
            function () use ($category_id) {
                return $this->convertCategory->convertItemData($this->categoryRepository->getById($category_id), true);
            },
            CacheStorage::TIME_1_D);
    }

    /**
     * @return CategoryItemInterface[]
     */
    function getCategoryTree()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::CATEGORY_TREE,
            function () {
                /**
                 * cache for CacheStorage::CATEGORY_TREE.
                 */
                $categories = Category::where(CategoryInterface::ATTR_PARENT_ID, 0)->get();
                return $this->convertCategory->convertCategoryChildrent($categories);
            }
        );
    }

    /**
     * @return CategoryItemInterface[]
     */
    function getCategoryTop()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::CATEGORY_TOP,
            function () {
                /**
                 * get config value
                 */
                $top_category = ConfigCategory::where("path", "=", ConfigCategory::TOP_CATEGORY);
                $values = Category::find(explode("&", $top_category->first()->value));
                return $this->convertCategory->convertCategoryChildrent($values, false);
            }
        );
    }

    function paperByCategory(int $category_id, int $limit = 6, $useCache = true)
    {
        /**
         * @var Category $category
         */
        $category = $this->categoryRepository->getById($category_id);
        if (empty($category)) {
            return [];
        }
        if (!$useCache) {
            return $this->convertPaper->convertPaperPaginate($category->getPaperByCategory($limit));
        }
        return $this->cacheManager->getAndCache(
            CacheStorage::CATEGORY_PAPER . $category_id . "_" . $limit . "_1",
            function () use ($category, $limit) {
                return $this->convertPaper->convertPaperPaginate($category->getPaperByCategory($limit));
            },
            CacheStorage::TIME_2_H
        );
    }

    function categoryTopForFirebase()
    {
        $topCategories = [];
        foreach ($this->getCategoryTop() as $category) {
            $topCategories[] = $category->toArray();
        }

        return $topCategories;
    }

    function addCategoryTopFirebase()
    {
        try {
            /**
             * tham chieu
             */
            $userRef = $this->firebaseDatabase->getReference('/newpaper/categoryTop');
            if ($userRef->getSnapshot()->getValue()) {
                $userRef->remove();
            }
            /**
             * upload data
             */
            $userRef->push($this->categoryTopForFirebase() ?: null);
            $snapshot = $userRef->getSnapshot();
            return [
                'status' => true,
                'value' => $snapshot->getValue()
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'value' => null
            ];
        }
    }

    function asyncCategory(): void
    {
        $categoryTree = $this->getCategoryTree();
        $uploadData = [];
        foreach ($categoryTree as $category) {
            $uploadData[] = $category->toArray();
        }
        $userRef = $this->firebaseDatabase->getReference('/newpaper/category');
        if ($userRef->getSnapshot()->getValue()) {
            $userRef->remove();
        }
        $userRef->push($uploadData);
    }
}
