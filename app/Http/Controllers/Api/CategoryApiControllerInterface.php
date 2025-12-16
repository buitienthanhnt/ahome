<?php
namespace App\Http\Controllers\Api;

interface CategoryApiControllerInterface{
    const CONTROLLER_NAME = 'Api\CategoryApiController';

    const CATEGORY_INFO = 'getCategoryInfo';
    const CATEGORY_TREE = 'getCategoryTree';
    const CATEGORY_TOP = 'getCategoryTop';
    const PAPER_BY_CATEGORY = 'getPaperCategory';

    public function getCategoryInfo(int $category_id);

    public function getCategoryTree();

    public function getCategoryTop();

     /**
     * @param int $category_id
     */
    public function getPaperCategory(int $category_id);
}