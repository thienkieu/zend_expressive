<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class ExamResultDTO implements \JsonSerializable
{
    protected $id;
    protected $examId;
    protected $candidate; 
    protected $title;
    protected $time; 
    protected $startDate;
    protected $remainingTime;
    protected $resultSummary;
    protected $examType;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->examId = $this->getExamId();
        $ret->candidate = $this->getCandidate();
        $ret->title = $this->getTitle();
        $ret->time = $this->getTime();
        $ret->startDate = $this->getStartDate() ? $this->getStartDate()->format(\Config\AppConstant::DateTimeFormat) : '';   
        $ret->remainingTime = $this->getRemainingTime();
        $ret->resultSummary = $this->getResultSummary();
        $ret->examType = $this->getExamType();
        
        return $ret;
    }


    /**
     * Get the value of candidate
     */ 
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * Set the value of candidate
     *
     * @return  self
     */ 
    public function setCandidate($candidate)
    {
        $this->candidate = $candidate;

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
     * Get the value of examId
     */ 
    public function getExamId()
    {
        return $this->examId;
    }

    /**
     * Set the value of examId
     *
     * @return  self
     */ 
    public function setExamId($examId)
    {
        $this->examId = $examId;

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
     * Get the value of remainingTime
     */ 
    public function getRemainingTime()
    {
        return $this->remainingTime;
    }

    /**
     * Set the value of remainingTime
     *
     * @return  self
     */ 
    public function setRemainingTime($remainingTime)
    {
        $this->remainingTime = $remainingTime;

        return $this;
    }

    /**
     * Get the value of resultSummary
     */ 
    public function getResultSummary()
    {
        return $this->resultSummary;
    }

    /**
     * Set the value of resultSummary
     *
     * @return  self
     */ 
    public function setResultSummary($resultSummary)
    {
        $this->resultSummary = $resultSummary;

        return $this;
    }

    /**
     * Get the value of examType
     */ 
    public function getExamType()
    {
        return $this->examType;
    }

    /**
     * Set the value of examType
     *
     * @return  self
     */ 
    public function setExamType($examType)
    {
        $this->examType = $examType;

        return $this;
    }
}
