<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class QuestionDTO implements \JsonSerializable
{
    protected $content;
    protected $order;

    /**
     * @var AnswerDTO[]
     */
    protected $answers;

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
        $ret->order = $this->getOrder();
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
}