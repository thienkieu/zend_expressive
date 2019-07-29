<?php

declare(strict_types=1);

namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */
class RandomQuestionDocument extends HasSubQuestionDocument
{
    /** @ODM\Field(type="bool") */
    protected $isDifferentSource;
    

    /**
     * Get the value of isDifferentSource
     */ 
    public function getIsDifferentSource()
    {
        return $this->isDifferentSource;
    }

    /**
     * Set the value of isDifferentSource
     *
     * @return  self
     */ 
    public function setIsDifferentSource($isDifferentSource)
    {
        $this->isDifferentSource = $isDifferentSource;

        return $this;
    }
}
