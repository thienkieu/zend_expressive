<?php
namespace ODMAuth\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="oauth_users", repositoryClass="\ODMAuth\Repositories\UserRepository")
 */

class UserDocument
{ 
  /** @ODM\Id(type="string") */
  protected $id;

  /** @ODM\Field(type="string") */
  private $username;

  /** @ODM\Field(type="string") */
  private $objectId;

  /** @ODM\Field(type="string") */
  private $password;

  /** @ODM\Field(type="string") */
  private $firstName;

  /** @ODM\Field(type="string") */
  private $lastName;

  /** @ODM\ReferenceMany(targetDocument="PermissionDocument", storeAs="id") */
  private $permissionDocument;

  public function __construct() {
    $this->permissionDocument = new ArrayCollection();
  }
  
  /**
   * Get the value of lastName
   */ 
  public function getLastName()
  {
    return $this->lastName;
  }

  /**
   * Set the value of lastName
   *
   * @return  self
   */ 
  public function setLastName($lastName)
  {
    $this->lastName = $lastName;

    return $this;
  }

  /**
   * Get the value of firstName
   */ 
  public function getFirstName()
  {
    return $this->firstName;
  }

  /**
   * Set the value of firstName
   *
   * @return  self
   */ 
  public function setFirstName($firstName)
  {
    $this->firstName = $firstName;

    return $this;
  }

  /**
   * Get the value of password
   */ 
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * Set the value of password
   *
   * @return  self
   */ 
  public function setPassword($password)
  {
    $this->password = $password;

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
   * Get the value of username
   */ 
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * Set the value of username
   *
   * @return  self
   */ 
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
   * Get the value of permissionDocument
   */ 
  public function getPermissionDocument()
  {
    return $this->permissionDocument;
  }

  /**
   * Set the value of permissionDocument
   *
   * @return  self
   */ 
  public function setPermissionDocument($permissionDocument)
  {
    $this->permissionDocument = $permissionDocument;

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
}