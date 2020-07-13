<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class SectionDTO implements \JsonSerializable
{
    protected $name;
    protected $description; 
    protected $id;
    protected $mark;
    protected $candidateMark;
    protected $comment;
    protected $isToeic;
    protected $toeicExpirationDate;
    protected $requiredQuestion;
    protected $isOption;
    
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
        $ret->mark = $this->getMark();
        $ret->candidateMark = $this->getCandidateMark();
        $ret->description = $this->getDescription(); 
        $ret->comment = $this->getComment();
        $ret->id = $this->getId();   
        $ret->isToeic = $this->getIsToeic();
        $ret->toeicExpirationDate = $this->getToeicExpirationDate() ? $this->getToeicExpirationDate()->format(\Config\AppConstant::DateTimeFormat) : '';
        $ret->isOptionQuestion = $this->getIsOption();
        $ret->numberOptionQuestion = $this->getRequiredQuestion();
        
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

    /**
     * Get the value of candidateMark
     */ 
    public function getCandidateMark()
    {
        return $this->candidateMark;
    }

    /**
     * Set the value of candidateMark
     *
     * @return  self
     */ 
    public function setCandidateMark($candidateMark)
    {
        $this->candidateMark = $candidateMark;

        return $this;
    }

    /**
     * Get the value of comment
     */ 
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @return  self
     */ 
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }
    
    /**
     * Get the value of isToeic
     */ 
    public function getIsToeic()
    {
        return $this->isToeic;
    }

    /**
     * Set the value of isToeic
     *
     * @return  self
     */ 
    public function setIsToeic($isToeic)
    {
        $this->isToeic = $isToeic;

        return $this;
    }

    /**
     * Get the value of toeicExpirationDate
     */ 
    public function getToeicExpirationDate()
    {
        return $this->toeicExpirationDate;
    }

    /**
     * Set the value of toeicExpirationDate
     *
     * @return  self
     */ 
    public function setToeicExpirationDate($toeicExpirationDate)
    {
        $this->toeicExpirationDate = $toeicExpirationDate;

        return $this;
    }

    /**
     * Get the value of requiredQuestion
     */ 
    public function getRequiredQuestion()
    {
        return $this->requiredQuestion;
    }

    /**
     * Set the value of requiredQuestion
     *
     * @return  self
     */ 
    public function setRequiredQuestion($requiredQuestion)
    {
        $this->requiredQuestion = $requiredQuestion;

        return $this;
    }

    /**
     * Get the value of isOption
     */ 
    public function getIsOption()
    {
        return $this->isOption;
    }

    /**
     * Set the value of isOption
     *
     * @return  self
     */ 
    public function setIsOption($isOption)
    {
        $this->isOption = $isOption;

        return $this;
    }
}
