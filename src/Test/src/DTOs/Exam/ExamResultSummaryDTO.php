<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class ExamResultSummaryDTO implements \JsonSerializable
{
    protected $name;
    protected $mark;
    protected $type;
    protected $comments;
    protected $candidateMark;
    protected $isScored;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->name = $this->getName();
        $ret->mark = $this->getMark(); 
        $ret->type = $this->getType();
        $ret->comments = $this->getComments() ? $this->getComments(): '';
        $ret->candidateMark = $this->getCandidateMark();
        $ret->isScored = $this->getIsScored();

        return $ret;
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
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of candidateMark
     */ 
    public function getCandidateMark()
    {
        return $this->candidateMark;
    }

    /**
     * Set the value of candidateMark
     *
     * @return  self
     */ 
    public function setCandidateMark($candidateMark)
    {
        $this->candidateMark = $candidateMark;

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

    /**
     * Get the value of comments
     */ 
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set the value of comments
     *
     * @return  self
     */ 
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }
}
