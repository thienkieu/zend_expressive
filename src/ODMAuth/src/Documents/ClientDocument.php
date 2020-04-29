<?php
namespace ODMAuth\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="oauth_clients", repositoryClass=ODMAuth\Repositories\ClientRepository::class)
 */

class ClientDocument
{ 
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $name;

  /** @ODM\Field(type="int") */
  private $userId;

  /** @ODM\Field(type="string") */
  private $secret;

  /** @ODM\Field(type="string") */
  private $redirect;

  /** @ODM\Field(type="boolean") */
  private $personalAccessClient;

  /** @ODM\Field(type="boolean") */
  private $passwordClient;

  /** @ODM\Field(type="boolean") */
  private $revoked;

  /** @ODM\Field(type="timestamp") */
  private $createdAt;

  /** @ODM\Field(type="timestamp") */
  private $updatedAt;

  /**
   * Get the value of revoked
   */ 
  public function getRevoked()
  {
    return $this->revoked;
  }

  /**
   * Set the value of revoked
   *
   * @return  self
   */ 
  public function setRevoked($revoked)
  {
    $this->revoked = $revoked;

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

  /**
   * Get the value of secret
   */ 
  public function getSecret()
  {
    return $this->secret;
  }

  /**
   * Set the value of secret
   *
   * @return  self
   */ 
  public function setSecret($secret)
  {
    $this->secret = $secret;

    return $this;
  }

  /**
   * Get the value of redirect
   */ 
  public function getRedirect()
  {
    return $this->redirect;
  }

  /**
   * Set the value of redirect
   *
   * @return  self
   */ 
  public function setRedirect($redirect)
  {
    $this->redirect = $redirect;

    return $this;
  }

  /**
   * Get the value of personalAccessClient
   */ 
  public function getPersonalAccessClient()
  {
    return $this->personalAccessClient;
  }

  /**
   * Set the value of personalAccessClient
   *
   * @return  self
   */ 
  public function setPersonalAccessClient($personalAccessClient)
  {
    $this->personalAccessClient = $personalAccessClient;

    return $this;
  }

  /**
   * Get the value of passwordClient
   */ 
  public function getPasswordClient()
  {
    return $this->passwordClient;
  }

  /**
   * Set the value of passwordClient
   *
   * @return  self
   */ 
  public function setPasswordClient($passwordClient)
  {
    $this->passwordClient = $passwordClient;

    return $this;
  }

  /**
   * Get the value of createdAt
   */ 
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * Set the value of createdAt
   *
   * @return  self
   */ 
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Get the value of updatedAt
   */ 
  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  /**
   * Set the value of updatedAt
   *
   * @return  self
   */ 
  public function setUpdatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }
}