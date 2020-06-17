<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class TypeDTO implements \JsonSerializable
{
    protected $id;
    protected $name; 
    protected $parentName;
    protected $isManualScored;
    protected $platform;
    protected $renderName;
    
    /**
     * @var TypeDTO[]
     */
    protected $subTypes;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->name = $this->getName();
        $ret->isManualScored = $this->getIsManualScored();
        $ret->subType = $this->getSubTypes();
        $ret->platform = $this->getPlatform();
        $ret->renderName = $this->getRenderName();
        
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
     * Get the value of parentName
     */ 
    public function getParentName()
    {
        return $this->parentName;
    }

    /**
     * Set the value of parentName
     *
     * @return  self
     */ 
    public function setParentName($parentName)
    {
        $this->parentName = $parentName;

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

    /**
     * Get the value of subTypes
     *
     * @return  TypeDTO[]
     */ 
    public function getSubTypes()
    {
        return $this->subTypes;
    }

    /**
     * Set the value of subTypes
     *
     * @param  TypeDTO[]  $subTypes
     *
     * @return  self
     */ 
    public function setSubTypes($subTypes)
    {
        $this->subTypes = $subTypes;

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
     * Get the value of renderName
     */ 
    public function getRenderName()
    {
        return $this->renderName;
    }

    /**
     * Set the value of renderName
     *
     * @return  self
     */ 
    public function setRenderName($renderName)
    {
        $this->renderName = $renderName;

        return $this;
    }
}
