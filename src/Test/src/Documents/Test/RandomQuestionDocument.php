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
    
    /** @ODM\Field(type="bool") */
    protected $isKeepQuestionOrder;

    /** @ODM\Field(type="bool") */
    protected $isRandomAnswer;
    
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

    /**
     * Get the value of isKeepQuestionOrder
     */ 
    public function getIsKeepQuestionOrder()
    {
        return $this->isKeepQuestionOrder;
    }

    /**
     * Set the value of isKeepQuestionOrder
     *
     * @return  self
     */ 
    public function setIsKeepQuestionOrder($isKeepQuestionOrder)
    {
        $this->isKeepQuestionOrder = $isKeepQuestionOrder;

        return $this;
    }

    /**
     * Get the value of isRandomAnswer
     */ 
    public function getIsRandomAnswer()
    {
        return $this->isRandomAnswer;
    }

    /**
     * Set the value of isRandomAnswer
     *
     * @return  self
     */ 
    public function setIsRandomAnswer($isRandomAnswer)
    {
        $this->isRandomAnswer = $isRandomAnswer;

        return $this;
    }
}
