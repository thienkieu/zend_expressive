<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class AnswerDTO implements \JsonSerializable
{
    protected $id;
    protected $content;
    protected $isCorrect;
    protected $order;
    protected $isUserChoice;
    

    /**
     * Get the value of isCorrect
     */ 
    public function getIsCorrect()
    {
        return $this->isCorrect;
    }

    /**
     * Set the value of isCorrect
     *
     * @return  self
     */ 
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();
        $ret->isCorrect = $this->getIsCorrect();
        $ret->order = $this->getOrder();
        $ret->isUserChoice = $this->getIsUserChoice() ? $this->getIsUserChoice(): false;
        $ret->id = $this->getId();
        return $ret;
    }

    /**
     * Get the value of order
     */ 
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */ 
    public function setOrder($order)
    {
        $this->order = $order;

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
     * Get the value of isUserChoice
     */ 
    public function getIsUserChoice()
    {
        return $this->isUserChoice;
    }

    /**
     * Set the value of isUserChoice
     * @var bool
     * 
     * @return  self
     */ 
    public function setIsUserChoice($isUserChoice)
    {
        $this->isUserChoice = $isUserChoice;

        return $this;
    }
}
