<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

use Test\DTOs\Question\QuestionDTO as Question;

class RandomQuestionDTO extends Question implements \JsonSerializable
{   
    /**
     * @var bool
     */
    protected $isDifferentSource;
    
    protected $numberSubQuestion;

    protected $isKeepQuestionOrder;

    protected $isRandomAnswer;
    
    protected $platform;
    
    /**
     * Get the value of isDifferentSource
     *
     * @return  bool
     */ 
    public function getIsDifferentSource()
    {
        return $this->isDifferentSource;
    }

    /**
     * Set the value of isDifferentSource
     *
     * @param  bool  $isDifferentSource
     *
     * @return  self
     */ 
    public function setIsDifferentSource(bool $isDifferentSource)
    {
        $this->isDifferentSource = $isDifferentSource;

        return $this;
    }

    /**
     * Get the value of numberQuestion
     */ 
    public function getNumberSubQuestion()
    {
        return $this->numberSubQuestion;
    }

    /**
     * Set the value of numberQuestion
     *
     * @return  self
     */ 
    public function setNumberSubQuestion(int $numberQuestion)
    {
        $this->numberSubQuestion = $numberQuestion;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->type = $this->getType();
        $ret->subType = $this->getSubType();
        $ret->typeId = $this->getTypeId();
        $ret->numberSubQuestion = $this->getNumberSubQuestion();    
        $ret->isDifferentSource = $this->getIsDifferentSource(); 
        $ret->isKeepQuestionOrder = $this->getIsKeepQuestionOrder();
        $ret->randomAnswer = $this->getIsRandomAnswer();
        $ret->mark = $this->getMark();        
        
        return $ret;
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

    /**
     * Get the value of platform
     */ 
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set the value of platform
     *
     * @return  self
     */ 
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }
}
