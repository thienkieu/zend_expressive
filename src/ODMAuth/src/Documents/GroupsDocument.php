<?php
namespace ODMAuth\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="oauth_groups", repositoryClass=ODMAuth\Repositories\GroupsRepository::class)
 */

class GroupsDocument
{ 
  /** @ODM\Id(type="string") */
  protected $id;

  /** @ODM\Field(type="string") */
  private $name;

  /** @ODM\Field(type="string") */
  private $description;


  /** @ODM\ReferenceMany(targetDocument=ODMAuth\Documents\PermissionDocument::class, storeAs="id") */
  private $permissionDocument;

  /** @ODM\ReferenceMany(targetDocument=ODMAuth\Documents\UserDocument::class, storeAs="id") */
  private $userDocument;

  public function __construct() {
    $this->permissionDocument = new ArrayCollection();
    $this->userDocument = new ArrayCollection();
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
   * Get the value of description
   */ 
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set the value of description
   *
   * @return  self
   */ 
  public function setDescription($description)
  {
    $this->description = $description;

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
   * Get the value of userDocument
   */ 
  public function getUserDocument()
  {
    return $this->userDocument;
  }

  /**
   * Set the value of userDocument
   *
   * @return  self
   */ 
  public function setUserDocument($userDocument)
  {
    $this->userDocument = $userDocument;

    return $this;
  }
}