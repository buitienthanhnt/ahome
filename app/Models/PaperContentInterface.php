<?php

namespace App\Models;

interface PaperContentInterface{
    const TABLE_NAME = 'paper_contents';

    const ATTR_TYPE = 'type';
    const ATTR_KEY = 'key';
    const ATTR_VALUE = 'value';
    const ATTR_DEPEND_VALUE = 'depend_value';
    const ATTR_PAPER_ID = 'paper_id';

    const TYPE_CONTENT = "content";
    const TYPE_TIMELINE = "timeline";
    const TYPE_IMAGE = 'image';
    const TYPE_SLIDER = 'slider_data';
    const TYPE_PRICE = 'price';
    const TYPE_VIDEO = 'video';

    const TYPE_TIMELINE_DEPEND = 'timeline_type';
    const TYPE_VIDEO_DEPEND = 'video_desc';
}

?>
