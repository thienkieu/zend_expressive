<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class WritingQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    private $answer;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();   
        $ret->type = $this->getType();
        $ret->subType = $this->getSubType();  
        $ret->answer = $this->getAnswer(); 
        return $ret;
    }

    /**
     * Get the value of answer
     */ 
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set the value of answer
     *
     * @return  self
     */ 
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }
}