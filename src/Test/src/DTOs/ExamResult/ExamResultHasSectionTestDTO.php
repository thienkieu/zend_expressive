<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class ExamResultHasSectionTestDTO extends ExamResultDTO implements \JsonSerializable
{
    protected $test;
    
    /**
     * Add the value of candidate
     *
     * @param  \Test\DTOs\Test\TestWithSectionDTO $test
     *
     * @return  self
     */  
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->examId = $this->getExamId();
        $ret->candidate = $this->getCandidate();
        $ret->test = $this->getTest();   
          
        return $ret;
    }

    /**
     * Get the value of test
     */ 
    public function getTest()
    {
        return $this->test;
    }

}
