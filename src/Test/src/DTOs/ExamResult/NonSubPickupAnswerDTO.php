<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class NonSubPickupAnswerDTO extends UserAnswerDTO implements \JsonSerializable
{
    protected $nonSub;

    /**
     * @var AnswerDTO[]
     */
    protected $answers;

    public function jsonSerialize() {
        $ret = parent::jsonSerialize();
        $ret->answers = $this->getAnswers();
        $ret->nonSub = true;
        
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

    /**
     * Get the value of nonSub
     */ 
    public function getNonSub()
    {
        return $this->nonSub;
    }

    /**
     * Set the value of nonSub
     *
     * @return  self
     */ 
    public function setNonSub($nonSub)
    {
        $this->nonSub = $nonSub;

        return $this;
    }
}
