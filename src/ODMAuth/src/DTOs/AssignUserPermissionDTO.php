<?php

declare(strict_types=1);

namespace ODMAuth\DTOs;

class AssignUserPermissionDTO implements \JsonSerializable
{
    protected $id;
    protected $userId;
    
     /**
     * @var []
     */
    protected $permissionsIds;
    
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->userId = $this->getUserId();
        $ret->permissionsIds = $this->getPermissionsIds();

        return $ret;
    }

    public function __construct()
    {
        $this->permissionsIds = [];
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
     * Get the value of userId
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */ 
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPermissionsIds()
    {
        return $this->permissionsIds;
    }

    public function setPermissionsIds($permissionsId)
    {
        $this->permissionsIds = $permissionsId;

        return $this;
    }
}
