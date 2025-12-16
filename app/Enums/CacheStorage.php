<?php

namespace App\Enums;

/**
 * @/method static static OptionOne()
 */
final class CacheStorage
{
    const CATEGORY_TREE = "CATEGORY_TREE";
    const CATEGORY_TOP = "CATEGORY_TOP";
    const CATEGORY_DETAIL_API = "CATEGORY_DETAIL_API_";
    const CATEGORY_PAPER = "CATEGORY_PAPER_";
    const CATEGORY_DETAIL_MODEL = "CATEGORY_DETAIL_MODEL_";
    const CATEGORY_TOP_HTML = 'CATEGORY_TOP_HTML';

    const WRITER_DETAIL_MODEL = "WRITER_DETAIL_MODEL_";
    const WRITER_PAPER = "WRITER_PAPER_";

    const PAPER_DETAIL_MODEL = "PAPER_DETAIL_MODEL_";
    const PAPER_DETAIL_API = "PAPER_DETAIL_API_";
    const PAPER_LIST_API = "PAPER_LIST_API_";
    const PAPER_SEARCH_API = "PAPER_SEARCH_API_";

    // home block
    const PAPER_MOST_POPULATOR = "PAPER_MOST_POPULATOR";
    const PAPER_MOST_RECENTS = "MOST_RECENTS";
    const PAPER_HIT = "PAPER_HIT";
    const PAPER_FORWARD = "PAPER_FORWARD";
    const PAPER_LIST_IMAGE = "PAPER_LIST_IMAGE";
    const PAPER_TIME_LINE = "PAPER_TIME_LINE";
    const PAPER_LAST_VIDEO = "PAPER_LAST_VIDEO";
    const PAPER_RANDOM = "PAPER_RANDOM";
    const PAPER_TAG = "PAPER_TAG";
    const PAPER_IDS = "PAPER_IDS_";

    const BLOCK_TOP_CATEGORY = "BLOCK_TOP_CATEGORY";
    const BLOCK_MOST_RECENTS = "BLOCK_MOST_RECENTS";
    const BLOCK_MORNING_TOP_RIGHT = "BLOCK_MORNING_TOP_RIGHT";
    const BLOCK_MORNING_TOP_LEFT = "BLOCK_MORNING_TOP_LEFT";
    const BLOCK_CENTER_CATEGORY = "BLOCK_CENTER_CATEGORY";
    const BLOCK_FOOTER = "BLOCK_FOOTER";

    const CUSTOM_CSS = 'CUSTOM_CSS';

    const TIME_15_M = 60*15;
    const TIME_30_M = 60*30;
    const TIME_1_H = 60*60;
    const TIME_2_H = 60*60*2;
    const TIME_3_H = 60*60*3;
    const TIME_4_H = 60*60*4;
    const TIME_6_H = 60*60*6;
    const TIME_8_H = 60*60*8;
    const TIME_12_H = 60*60*12;
    const TIME_1_D = 60*60*24;
    const TIME_2_D = 60*60*24*2;
    const TIME_3_D = 60*60*24*3;
    const TIME_7_D = 60*60*24*7;
}
