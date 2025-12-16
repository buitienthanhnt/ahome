<?php

namespace App\Api\Data\Paper;

use Illuminate\Contracts\Support\Arrayable;

interface InfoInterface extends Arrayable
{
    const VIEW_COUNT = "viewCount";
    const COMMENT_COUNT = "commentCount";
    const LIKE = "like";
    const HEART = "heart";

    /**
     * @param int $view_count
     * return $this
     */
    function setViewCount(int $view_count);

    /**
     * @return int
     */
    function getViewCount();

    /**
     * @param int $comment_count
     * return $this
     */
    function setCommentCount(int $comment_count);

    /**
     * @return int
     */
    function getCommentCount();

    /**
     * @param int $like
     * @return $this
     */
    function setLike(int $like);

    /**
     * @return int
     */
    function getLike();

    /**
     * @param int $heart
     * @return $this
     */
    function setHeart(int $heart);

    /**
     * @return int
     */
    function getHeart();
}
