<?php
namespace ODMAuth\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="oauth_refresh_tokens", repositoryClass=ODMAuth\Repositories\RefreshTokenRepository::class)
 */

class RefreshTokenDocument
{ 
  /** @ODM\Id(strategy="NONE", type="string") */
  protected $id;

  /** @ODM\Field(type="string") */
  private $accessTokenId;

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
   * Get the value of accessTokenId
   */ 
  public function getAccessTokenId()
  {
    return $this->accessTokenId;
  }

  /**
   * Set the value of accessTokenId
   *
   * @return  self
   */ 
  public function setAccessTokenId($accessTokenId)
  {
    $this->accessTokenId = $accessTokenId;

    return $this;
  }
}