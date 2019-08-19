<?php

declare(strict_types=1);

namespace ODMAuth\DTOs;

class PermissionDTO implements \JsonSerializable
{
    protected $id;
    protected $name;
    
     /**
     * @var String[]
     */
    protected $accessFunctions;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->name = $this->getName();
        $ret->accessFunctions = $this->getAccessFunctions();

        return $ret;
    }

    public function __construct()
    {
        $this->accessFunctions = [];
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
     * Get the value of accessFunctions
     *
     * @return  String[]
     */ 
    public function getAccessFunctions()
    {
        return $this->accessFunctions;
    }

    /**
     * Set the value of accessFunctions
     *
     * @param  String[]  $accessFunctions
     *
     * @return  self
     */ 
    public function setAccessFunctions($accessFunctions)
    {
        $this->accessFunctions = $accessFunctions;

        return $this;
    }
}
