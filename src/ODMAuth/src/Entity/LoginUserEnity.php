<?php

declare(strict_types=1);

namespace ODMAuth\Entity;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Zend\Expressive\Authentication\OAuth2\Entity;
use Zend\Expressive\Authentication\OAuth2\Entity\UserEntity;

class LoginUserEnity extends UserEntity
{
    protected $userData = [];

    public function __construct($identifier)
    {
        parent::__construct($identifier);
    }

    public function setUserData($key, $value) {
        $this->userData[$key] = $value;
    }

    public function getUserData($key = '') {
        if (empty($key)) return $this->userData;
        return $this->userData[$key];
    }
}
