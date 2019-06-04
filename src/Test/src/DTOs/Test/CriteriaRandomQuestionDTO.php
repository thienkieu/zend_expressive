<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class CriteriaRandomQuestionDTO
{
    /**
     * @var int
     */
    protected $numberSubQuestion;

    /**
     * @var string
     */
    protected $subType;

    /**
     * Get the value of numberQuestion
     *
     * @return  int
     */ 
    public function getNumberSubQuestion()
    {
        return $this->numberSubQuestion;
    }

    /**
     * Set the value of numberQuestion
     *
     * @param  int  $numberQuestion
     *
     * @return  self
     */ 
    public function setNumberSubQuestion(int $numberQuestion)
    {
        $this->numberSubQuestion = $numberQuestion;

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
