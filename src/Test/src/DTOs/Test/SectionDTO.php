<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class SectionDTO implements \JsonSerializable
{
    protected $name;
    protected $description; 
    
    /**
     * @var QuestionDTO[]
     */
    protected $questions;


    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->questions = [];
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

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Get the value of questions
     *
     * @return  QuestionDTO[]
     */ 
    public function setQuestions($questions)
    {
        $this->questions = $questions;
        return $this;
    }

    /**
     * Add the value of question
     *
     * @param  QuestionDTO[]  $question
     *
     * @return  self
     */ 
    public function addQuestions($question)
    {
        $this->questions[] = $question;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->name = $this->getName();
        $ret->description = $this->getDescription();    
        $questions = $this->getQuestions();
        $dtoQuestions = [];
        foreach ($questions as $question) {          
            $info = json_decode(json_encode($question->getQuestionInfo()));
            $info->generateFrom = $question->getGenerateFrom();
            $info->id = $question->getId();
            $dtoQuestions[] = $info;
        }

        $ret->questions = $dtoQuestions;
        
        return $ret;
    }
}
