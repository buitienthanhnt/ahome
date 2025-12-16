<?php
namespace App\Models;

interface CommentInterface{
    const TABLE_NAME = "comments";

    const ATTR_EMAIL = 'email';
    const ATTR_NAME = 'name';
    const ATTR_SUBJECT = 'subject';
    const ATTR_CONTENT = 'content';
    const ATTR_SHOW = 'show';
    const ATTR_LIKE = 'like';
    const ATTR_PARENT_ID = 'parent_id';
    const ATTR_PAPER_ID = PaperInterface::PRIMARY_ALIAS;

    const PARAM_ACTION = 'action';
    const ACTION_LIKE = 'like';
    const ACTION_DISLIKE = 'dislike';

    const LIKED_IDS = 'commentLiked_ids';
}
