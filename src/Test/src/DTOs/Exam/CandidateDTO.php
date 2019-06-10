<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class CandidateDTO implements \JsonSerializable
{
    protected $id;
    protected $objectId;
    protected $name; 
    protected $type; 
    protected $email; 

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Get the value of objectId
     */ 
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set the value of objectId
     *
     * @return  self
     */ 
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->type = $this->getType();
        $ret->name = $this->getName();
        $ret->email = $this->getEmail();   
        $ret->objectId = $this->getObjectId();   
        return $ret;
    }
}