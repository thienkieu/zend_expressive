<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class CriteriaRandomQuestionDTO
{
    /**
     * @var int
     */
    protected $numberQuestion;

    /**
     * @var string
     */
    protected $subType;

    /**
     * Get the value of numberQuestion
     *
     * @return  int
     */ 
    public function getNumberQuestion()
    {
        return $this->numberQuestion;
    }

    /**
     * Set the value of numberQuestion
     *
     * @param  int  $numberQuestion
     *
     * @return  self
     */ 
    public function setNumberQuestion(int $numberQuestion)
    {
        $this->numberQuestion = $numberQuestion;

        return $this;
    }

    /**
     * Get the value of subType
     *
     * @return  string
     */ 
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * Set the value of subType
     *
     * @param  string  $subType
     *
     * @return  self
     */ 
    public function setSubType(string $subType)
    {
        $this->subType = $subType;

        return $this;
    }
}
