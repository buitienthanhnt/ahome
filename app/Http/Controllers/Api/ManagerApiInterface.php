<?php
namespace App\Http\Controllers\Api;

interface ManagerApiInterface{
    const CONTROLLER_NAME = '\App\Http\Controllers\Api\ManagerApiController';

    const HOME_INFO = 'homeInfo';
    const TAGS = 'tags';
    const EVENT_DAY = 'eventDay';

    const BLOCK_TYPE = 'blockType';

    const BLOCK_TYPE_CATEGORY = 'Category';
    const BLOCK_TYPE_TOPNEW = 'TopNew';
    const BLOCK_TYPE_POPULAR = 'Popular';
    const BLOCK_TYPE_TOPSEARCH = 'TopSearch';
    const BLOCK_TYPE_FORMARD = 'Forward';
    const BLOCK_TYPE_PRO = 'Pro';
    const BLOCK_TYPE_TIMELINE = 'TimeLine';
    const BLOCK_TYPE_VIDEO = 'Video';
    const BLOCK_TYPE_IMAGES = 'Images';
    const BLOCK_TYPE_CHART = 'Chart';
    const BLOCK_TYPE_LISTWRITER = 'ListWriter';
    const BLOCK_TYPE_SEARCHALL = 'SearchAll';
    const BLOCK_TYPE_RANDOM = 'Random';
    const BLOCK_TYPE_DEFAULT = 'Default';

    public function homeInfo();

    public function tags();

    public function eventDay(string $date);
}
