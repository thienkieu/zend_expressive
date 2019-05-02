<?php

declare(strict_types=1);

namespace Test\DTOs;

class AnswerDTO implements \JsonSerializable
{
    protected $content;
    protected $isCorrect;
    
    

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
        $ret->isCorrect = $this->isCorrect;
        return $ret;
    }
}
