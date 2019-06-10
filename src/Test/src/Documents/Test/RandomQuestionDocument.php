<?php

declare(strict_types=1);

namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */
class RandomQuestionDocument
{
    /** @ODM\Id */
    protected $id;

    /** @ODM\Field(type="string") */
    protected $type;

    /** @ODM\Field(type="bool") */
    protected $isDifferentSource;
    
    /** @ODM\Field(type="int") */
    protected $numberSubQuestion;

    /** @ODM\Field(type="string") */
    protected $subType;

    /**
     * Get the value of type
     *
     * @return  string
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param  string  $type
     *
     * @return  self
     */ 
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of isDifferentSource
     *
     * @return  bool
     */ 
    public function getIsDifferentSource()
    {
        return $this->isDifferentSource;
    }

    /**
     * Set the value of isDifferentSource
     *
     * @param  bool  $isDifferentSource
     *
     * @return  self
     */ 
    public function setIsDifferentSource(bool $isDifferentSource)
    {
        $this->isDifferentSource = $isDifferentSource;

        return $this;
    }

    /**
     * Get the value of subType
     *
     * @return  string
     */ 
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * Set the value of subType
     *
     * @param  string  $subType
     *
     * @return  self
     */ 
    public function setSubType(string $subType)
    {
        $this->subType = $subType;

        return $this;
    }

    /**
     * Get the value of numberQuestion
     */ 
    public function getNumberSubQuestion()
    {
        return $this->numberSubQuestion;
    }

    /**
     * Set the value of numberQuestion
     *
     * @return  self
     */ 
    public function setNumberSubQuestion($numberQuestion)
    {
        $this->numberSubQuestion = $numberQuestion;

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
}
