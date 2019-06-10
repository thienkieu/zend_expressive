<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class QuestionDTO implements \JsonSerializable
{
    protected $id;
    protected $generateFrom;
    protected $questionInfo;

    /**
     * Get the value of questionInfo
     */ 
    public function getQuestionInfo()
    {
        return $this->questionInfo;
    }

    /**
     * Set the value of questionInfo
     *
     * @return  self
     */ 
    public function setQuestionInfo($questionInfo)
    {
        $this->questionInfo = $questionInfo;

        return $this;
    }

    /**
     * Get the value of generateFrom
     */ 
    public function getGenerateFrom()
    {
        return $this->generateFrom;
    }

    /**
     * Set the value of generateFrom
     *
     * @return  self
     */ 
    public function setGenerateFrom($generateFrom)
    {
        $this->generateFrom = $generateFrom;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->generateFrom = $this->getGenerateFrom();
        $ret->questionInfo = $this->getQuestionInfo();       
        return $ret;
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
