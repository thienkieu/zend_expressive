<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class UserAnswerDTO implements \JsonSerializable
{
    protected $examId;
    protected $candidateId;
    protected $sectionId; 
    protected $questionId;
    protected $questionInfoId; 
    protected $subQuestionId;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->examId = $this->getExamId();
        $ret->candidateId = $this->getCandidateId();
        $ret->sectionId = $this->getSectionId();
        $ret->questionId = $this->getQuestionId();
        $ret->questionInfoId = $this->getQuestionInfoId();   
        $ret->subQuestionId = $this->getSubQuestionId(); 
          
        return $ret;
    }

    /**
     * Get the value of subQuestionId
     */ 
    public function getSubQuestionId()
    {
        return $this->subQuestionId;
    }

    /**
     * Set the value of subQuestionId
     *
     * @return  self
     */ 
    public function setSubQuestionId($subQuestionId)
    {
        $this->subQuestionId = $subQuestionId;

        return $this;
    }

    /**
     * Get the value of questionInfoId
     */ 
    public function getQuestionInfoId()
    {
        return $this->questionInfoId;
    }

    /**
     * Set the value of questionInfoId
     *
     * @return  self
     */ 
    public function setQuestionInfoId($questionInfoId)
    {
        $this->questionInfoId = $questionInfoId;

        return $this;
    }

    /**
     * Get the value of questionId
     */ 
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set the value of questionId
     *
     * @return  self
     */ 
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get the value of sectionId
     */ 
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * Set the value of sectionId
     *
     * @return  self
     */ 
    public function setSectionId($sectionId)
    {
        $this->sectionId = $sectionId;

        return $this;
    }

    /**
     * Get the value of candidateId
     */ 
    public function getCandidateId()
    {
        return $this->candidateId;
    }

    /**
     * Set the value of candidateId
     *
     * @return  self
     */ 
    public function setCandidateId($candidateId)
    {
        $this->candidateId = $candidateId;

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
