<?php

namespace App\Api\Data\Paper;

class PaperDetail extends PaperItem implements PaperDetailInterface
{


    function setContents($contents)
    {
        return $this->setData(self::CONTENTS, $contents);
    }

    function getContents()
    {
        return $this->getData(self::CONTENTS);
    }

    function setSuggest($suggest)
    {
        return $this->setData(self::SUGGEST, $suggest);
    }

    function getSuggest()
    {
        return $this->getData(self::SUGGEST);
    }

    function setTags($tags)
    {
        return $this->setData(self::TAGS, $tags);
    }

    function getTags()
    {
        return $this->getData(self::TAGS);
    }
}
