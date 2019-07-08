<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class EditTestOfExamDTO
{
    protected $test;
    protected $id;
    
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

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of test
     */ 
    public function getTest()
    {
        return $this->test;
    }
}
