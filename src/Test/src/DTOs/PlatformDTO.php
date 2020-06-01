<?php

declare(strict_types=1);

namespace Test\DTOs;

class PlatformDTO implements \JsonSerializable
{
    protected $id;
    protected $name; 
    protected $parentName;
    protected $subPlatforms;

    /**
     * @var PlatformDTO[]
     */
    protected $subTypes;

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->name = $this->getName();
        $ret->subPlatforms = $this->getSubPlatforms();
        
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
     * Get the value of subPlatforms
     *
     * @return  PlatformDTO[]
     */ 
    public function getSubPlatforms()
    {
        return $this->subPlatforms;
    }

    /**
     * Set the value of subPlatforms
     *
     * @param  PlatformDTO[]  $subPlatforms
     *
     * @return  self
     */ 
    public function setSubPlatforms($subPlatforms)
    {
        $this->subPlatforms = $subPlatforms;

        return $this;
    }
}
