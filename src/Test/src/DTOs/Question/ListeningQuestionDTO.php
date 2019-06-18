<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class ListeningQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    protected $path;
    protected $repeat;
        
    /**
     * Get the value of repeat
     */ 
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * Set the value of repeat
     *
     * @return  self
     */ 
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();
        $ret->repeat = $this->getRepeat();
        $ret->subQuestions = $this->getSubQuestions();
        $ret->path = $this->getPath();
        $ret->type = $this->getType();
        $ret->subType = $this->getSubType();
        $ret->id = $this->getId();
        $ret->source = $this->getSource();
        return $ret;
    }

}
