<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class NonSubQuestionDTO extends QuestionDTO implements \JsonSerializable
{ 
    /**
     * @var AnswerDTO[]
     */
    protected $answers;
    
    public function jsonSerialize() {
        $ret = parent::jsonSerialize();        
        return $ret;
    }
    

    /**
     * Get the value of answers
     *
     * @return  AnswerDTO[]
     */ 
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set the value of answers
     *
     * @param  AnswerDTO[]  $answers
     *
     * @return  self
     */ 
    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }
}
