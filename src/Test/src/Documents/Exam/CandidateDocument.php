<?php
namespace Test\Documents\Exam;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */

class CandidateDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $objectId;

  /** @ODM\Field(type="string") */
  protected $type;
  
  /** @ODM\Field(type="string") */
  protected $email;
  
  /** @ODM\Field(type="string") */
  protected $name;


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
}