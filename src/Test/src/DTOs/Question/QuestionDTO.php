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
    

    /**
     * @var SubQuestionDTO[]
     */
    protected $subQuestions;
    
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
}
