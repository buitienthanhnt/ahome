<?php
namespace App\Http\Controllers\Frontend;

interface ManagerControllerInterface{
    const CONTROLLER_NAME = 'Frontend\ManagerController';

    const HOME_PAGE = 'homePage';
    const FORNT_CATEFORY_VIEW = 'categoryView';
    const FRONT_PAPER_DETAIL = 'paperDetail';
    const FRONT_TAG_VIEW = 'tagView';
    const FRONT_SEARCH = 'search';
    const LOAD_MORE = 'loadMore';
    const MOST_POPULATOR_HTML = 'mostPopulator';
    const MOST_LIKE_HTML = 'likeMost';
    const MOST_TRENDING_HTML = 'trendingHtml';
    const REDIRECT = 'redirect';

    public function homePage();

    /**
     * @param string $category_alias
     * @return mixed
     */
    public function categoryView(string $category_alias);

    /**
     * @param string $alias
     * @param int    $paper_id
     * @return mixed
     */
    public function paperDetail(string $alias, int $paper_id);

    /**
     * @param string $tag
     * @return mixed
     */
    public function tagView(string $tag);

    public function search();

    public function loadMore();

    public function mostPopulator();

    public function likeMost();

    public function trendingHtml();

    public function redirect();
}
