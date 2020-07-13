<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class QuestionDTO
{
    protected $id;
    protected $type;
    protected $content;
    protected $subType;
    protected $typeId;
    protected $platformId;
    protected $platform;
    protected $source;
    protected $sourceId;
    protected $mark;
    protected $comment;
    protected $candidateMark;
    protected $order;
    protected $systemType;
    protected $user;
    protected $renderType;
    protected $referId;
    
     /**
     * @var SubQuestionDTO[]
     */
    protected $subQuestions;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();
        $ret->type = $this->getType();
        $ret->subType = $this->getSubType();
        $ret->typeId = $this->getTypeId();
        
        $ret->id = $this->getId();
        $ret->source = $this->getSource();
        $ret->sourceId = $this->getSourceId();
        $ret->platform = $this->getPlatform();
        $ret->platformId = $this->getPlatformId();
        $ret->mark = $this->getMark();
        $ret->comment = $this->getComment();
        $ret->candidateMark = $this->getCandidateMark();
        $ret->systemType = $this->getSystemType();
        $ret->user = $this->getUser();
        $ret->renderType = $this->getRenderType();
        $ret->referId = $this->getReferId();
        return $ret;
    }

    public function __construct()
    {
        $this->subQuestions = [];
        $this->comment = '';
        $this->source = '';
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
     * Get the value of sourceId
     */ 
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Set the value of sourceId
     *
     * @return  self
     */ 
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * Get the value of typeId
     */ 
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set the value of typeId
     *
     * @return  self
     */ 
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
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
     * Get the value of systemType
     */ 
    public function getSystemType()
    {
        return $this->systemType;
    }

    /**
     * Set the value of systemType
     *
     * @return  self
     */ 
    public function setSystemType($systemType)
    {
        $this->systemType = $systemType;

        return $this;
    }

    /**
     * Get the value of platform
     */ 
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set the value of platform
     *
     * @return  self
     */ 
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get the value of platformId
     */ 
    public function getPlatformId()
    {
        return $this->platformId;
    }

    /**
     * Set the value of platformId
     *
     * @return  self
     */ 
    public function setPlatformId($platformId)
    {
        $this->platformId = $platformId;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of renderType
     */ 
    public function getRenderType()
    {
        return $this->renderType;
    }

    /**
     * Set the value of renderType
     *
     * @return  self
     */ 
    public function setRenderType($renderType)
    {
        $this->renderType = $renderType;

        return $this;
    }

    /**
     * Get the value of referId
     */ 
    public function getReferId()
    {
        return $this->referId;
    }

    /**
     * Set the value of referId
     *
     * @return  self
     */ 
    public function setReferId($referId)
    {
        $this->referId = $referId;

        return $this;
    }
}
