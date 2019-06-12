<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class PinInfoDTO implements \JsonSerializable
{
    protected $examId;
    protected $candidate;    

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->examId = $this->getExamId();
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
