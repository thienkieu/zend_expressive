<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class SubTypeDTO implements \JsonSerializable
{
    protected $id;
    protected $name; 
    protected $isManualScored;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->name = $this->getName();
        $ret->isManualScored = $this->getIsManualScored();
        
        return $ret;
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
     * Get the value of isManualScored
     */ 
    public function getIsManualScored()
    {
        return $this->isManualScored;
    }

    /**
     * Set the value of isManualScored
     *
     * @return  self
     */ 
    public function setIsManualScored($isManualScored)
    {
        $this->isManualScored = $isManualScored;

        return $this;
    }
}
