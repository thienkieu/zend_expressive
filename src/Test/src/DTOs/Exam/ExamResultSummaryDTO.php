<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class ExamResultSummaryDTO implements \JsonSerializable
{
    protected $name;
    protected $mark;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->name = $this->getName();
        $ret->mark = $this->getMark(); 
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
}
