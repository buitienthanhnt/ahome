<?php
namespace App\Api\Data\Paper;

interface PaperItemInterface extends BaseAttributeInterface{
    const TITLE = 'title';
    const URL = 'url';
    const SHORT_CONTENT = 'shortContent';
    const IMAGE = 'image';
    const ACTIVE = 'active';
    const WRITER = 'writer';
    const INFO = 'info';
    const SHOW_HOT = 'showHot';

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $short_content
     * @return $this
     */
    public function setShortContent(string $short_content);

    /**
     * @return string
     */
    public function getShortContent();

    /**
     * @param string $image
     * @return $this
     */
    public function setImage(string $image);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active);

    /**
     * @return bool
     */
    public function getActive();


    /**
     * @param bool $hot
     * @return $this
     */
    public function setShowHot(bool $hot);

    /**
     * @return bool
     */
    public function getShowHot();

    /**
     * @param mixed $writer
     * @return $this
     */
    public function setWriter($writer);

    /**
     * @return mixed
     */
    public function getWriter();

    /**
     * @param \App\Api\Data\Paper\Info $info
     * @return $this
     */
    public function setInfo($info);

    /**
     * @return \App\Api\Data\Paper\Info
     */
    public function getInfo();
}
