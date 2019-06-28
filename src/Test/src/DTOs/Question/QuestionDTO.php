<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class QuestionDTO
{
    protected $id;
    protected $type;
    protected $content;
    protected $subType;
    protected $source;
    protected $mark;
    protected $candidateMark;
    protected $comment;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent() ? $this->getContent() : '';
        $ret->type = $this->getType();
        $ret->subType = $this->getSubType();
        $ret->id = $this->getId();
        $ret->source = $this->getSource() ? $this->getSource(): '';
        $ret->mark = $this->getMark() ? $this->getMark() : 0;
        $ret->candidateMark = $this->getCandidateMark() ? $this->getCandidateMark() : 0;
        $ret->comment = $this->getComment() ? $this->getComment(): '';

        return $ret;
    }

    /**
     * @var SubQuestionDTO[]
     */
    protected $subQuestions;

    public function __construct()
    {
        $this->subQuestions = [];
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
     * Get the value of subType
     */ 
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * Set the value of subType
     *
     * @return  self
     */ 
    public function setSubType($subType)
    {
        $this->subType = $subType;

        return $this;
    }

    /**
     * Get the value of source
     */ 
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set the value of source
     *
     * @return  self
     */ 
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get the value of subQuestions
     *
     * @return  SubQuestionDTO[]
     */ 
    public function getSubQuestions()
    {
        return $this->subQuestions;
    }

    /**
     * Set the value of subQuestions
     *
     * @param  SubQuestionDTO[]  $subQuestions
     *
     * @return  self
     */ 
    public function setSubQuestions($subQuestions)
    {
        $this->subQuestions = $subQuestions;

        return $this;
    }

    /**
     * Add the value of subQuestions
     *
     * @param  SubQuestionDTO  $subQuestion
     *
     * @return  self
     */ 
    public function addSubQuestion($subQuestion)
    {
        $this->subQuestions[] = $subQuestions;

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
}
