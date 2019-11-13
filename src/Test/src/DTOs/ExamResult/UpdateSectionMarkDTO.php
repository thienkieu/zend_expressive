<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class UpdateSectionMarkDTO implements \JsonSerializable
{
    protected $examId;
    protected $candidateId;
    protected $sectionId; 
    protected $comment;
    protected $mark;
    protected $isToeic;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->examId = $this->getExamId();
        $ret->candidateId = $this->getCandidateId();
        $ret->sectionId = $this->getSectionId();
        $ret->comment = $this->getComment();
        $ret->mark = $this->getMark();
        $ret->isToeic = $this->getIsToeic();
        
        return $ret;
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

    /**
     * Get the value of comment
     */ 
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @return  self
     */ 
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of mark
     */ 
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set the value of mark
     *
     * @return  self
     */ 
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get the value of isToeic
     */ 
    public function getIsToeic()
    {
        return $this->isToeic;
    }

    /**
     * Set the value of isToeic
     *
     * @return  self
     */ 
    public function setIsToeic($isToeic)
    {
        $this->isToeic = $isToeic;

        return $this;
    }
}
