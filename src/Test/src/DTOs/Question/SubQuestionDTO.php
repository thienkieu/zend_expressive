<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class SubQuestionDTO implements \JsonSerializable
{
    protected $id;
    protected $content;
    protected $order;
    protected $mark;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->mark = \Config\AppConstant::DefaultSubQuestionMark;
    }
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
        $ret->order = $this->getOrder() ? $this->getOrder(): 0;
        $ret->id = $this->getId();
        $ret->mark = $this->getMark() ? $this->getMark(): 0;
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
}