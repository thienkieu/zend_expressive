<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class AnswerDTO implements \JsonSerializable
{
    protected $id;

    /**
     * Get the value of answers
     *
     * @return  bool
     */ 
    protected $isUserChoice;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->isUserChoice = $this->getIsUserChoice();
          
        return $ret;
    }

    /**
     * Get the value of isUserChoice
     */ 
    public function getIsUserChoice()
    {
        return $this->isUserChoice;
    }

    /**
     * Set the value of isUserChoice
     *
     * @return  self
     */ 
    public function setIsUserChoice($isUserChoice)
    {
        $this->isUserChoice = $isUserChoice;

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
}
