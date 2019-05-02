<?php

declare(strict_types=1);

namespace Test\DTOs;

class SectionDTO
{
    protected $id;
    protected $type;
    protected $content;

    /**
     * @var QuestionDTO[]
     */
    protected $questions;
    
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
     * Get the value of questions
     *
     * @return  QuestionDTO[]
     */ 
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set the value of questions
     *
     * @param  QuestionDTO[]  $questions
     *
     * @return  self
     */ 
    public function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }
}
