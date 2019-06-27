<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class ReadingQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    private $numberCorrectSubQuestion;
    
    public function jsonSerialize() {
        $ret = parent::jsonSerialize();
        $ret->subQuestions = $this->getSubQuestions();
        $ret->numberCorrectSubQuestion = $this->getNumberCorrectSubQuestion() ? $this->getNumberCorrectSubQuestion() : 0;
       
        return $ret;
    }
    
    /**
     * Get the value of numberCorrectSubQuestion
     */ 
    public function getNumberCorrectSubQuestion()
    {
        return $this->numberCorrectSubQuestion;
    }

    /**
     * Set the value of numberCorrectSubQuestion
     *
     * @return  self
     */ 
    public function setNumberCorrectSubQuestion($numberCorrectSubQuestion)
    {
        $this->numberCorrectSubQuestion = $numberCorrectSubQuestion;

        return $this;
    }
}
