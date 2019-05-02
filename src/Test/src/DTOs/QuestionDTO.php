<?php

declare(strict_types=1);

namespace Test\DTOs;

class QuestionDTO implements \JsonSerializable
{
    protected $content;
    protected $title;

    /**
     * @var AnswerDTO[]
     */
    protected $answers;

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

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

    /**
     * Get the value of answers
     *
     * @return  AnswerDTO[]
     */ 
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set the value of answers
     *
     * @param  AnswerDTO[]  $answers
     *
     * @return  self
     */ 
    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();
        $ret->answers = $this->getAnswers();
        return $ret;
    }
}