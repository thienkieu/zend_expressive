<?php
namespace ODMAuth\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="oauth_auth_codes", repositoryClass="\ODMAuth\Repositories\AuthCodeRepository")
 */

class AuthCodeDocument
{ 
  /** @ODM\Id(strategy="NONE", type="string") */
  protected $id;

  /** @ODM\Field(type="int") */
  private $userId;

  /** @ODM\Field(type="int") */
  private $clientId;

  /** @ODM\Field(type="string") */
  private $scopes;

  /** @ODM\Field(type="boolean") */
  private $revoked;

  /** @ODM\Field(type="timestamp") */
  private $expiresAt;


  /**
   * Get the value of expiresAt
   */ 
  public function getExpiresAt()
  {
    return $this->expiresAt;
  }

  /**
   * Set the value of expiresAt
   *
   * @return  self
   */ 
  public function setExpiresAt($expiresAt)
  {
    $this->expiresAt = $expiresAt;

    return $this;
  }

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
   * Get the value of scopes
   */ 
  public function getScopes()
  {
    return $this->scopes;
  }

  /**
   * Set the value of scopes
   *
   * @return  self
   */ 
  public function setScopes($scopes)
  {
    $this->scopes = $scopes;

    return $this;
  }

  /**
   * Get the value of clientId
   */ 
  public function getClientId()
  {
    return $this->clientId;
  }

  /**
   * Set the value of clientId
   *
   * @return  self
   */ 
  public function setClientId($clientId)
  {
    $this->clientId = $clientId;

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