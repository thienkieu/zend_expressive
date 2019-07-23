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
        $ret->mark = $this->getMark();        
        
        return $ret;
    }
}
