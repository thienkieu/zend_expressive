<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class ExamResultDTO implements \JsonSerializable
{
    protected $id;
    protected $examId;
    protected $candidate; 

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->candidate = $this->getCandidate();   
          
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
}
