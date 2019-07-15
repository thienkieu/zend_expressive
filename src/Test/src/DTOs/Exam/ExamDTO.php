<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class ExamDTO implements \JsonSerializable
{
    protected $id;
    protected $test;
    protected $title;
    protected $candidates; 
    protected $time; 
    protected $startDate; 
    protected $isScored;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->test = $this->getTest();
        $ret->time = $this->getTime();
        $ret->title = $this->getTitle();
        $ret->candidates = $this->getCandidates();   
        $ret->isScored = $this->getIsScored();
        $ret->startDate = $this->getStartDate()->format(\Config\AppConstant::DateTimeFormat); 
          
        return $ret;
    }

    /**
     * Get the value of startDate
     */ 
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate
     *
     * @return  self
     */ 
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the value of time
     */ 
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the value of time
     *
     * @return  self
     */ 
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the value of candidates
     */ 
    public function getCandidates()
    {
        return $this->candidates;
    }

    /**
     * Add the value of candidate
     *
     * @param  CandidateDTO[]  $candidates
     *
     * @return  self
     */ 
    public function setCandidates($candidates)
    {
        $this->candidates = $candidates;

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

    /**
     * Add the value of candidate
     *
     * @param  \Test\DTOs\Test\BaseTestDTO $test
     *
     * @return  self
     */  
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of isScored
     */ 
    public function getIsScored()
    {
        return $this->isScored;
    }

    /**
     * Set the value of isScored
     *
     * @return  self
     */ 
    public function setIsScored($isScored)
    {
        $this->isScored = $isScored;

        return $this;
    }
}
