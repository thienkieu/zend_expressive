<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class VerbalQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    private $answer;
    
    public function jsonSerialize() {
        $ret = parent::jsonSerialize(); 
        $ret->answer = $this->getAnswer() ? $this->getAnswer() : '';
        
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