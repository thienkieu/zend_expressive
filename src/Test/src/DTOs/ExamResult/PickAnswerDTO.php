<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class PickAnswerDTO extends UserAnswerDTO implements \JsonSerializable
{
    /**
     * @var AnswerDTO[]
     */
    protected $answers;

    public function jsonSerialize() {
        $ret = parent::jsonSerialize();
        $ret->answers = $this->getAnswers();
          
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
