<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="type", repositoryClass="\Test\Repositories\TypeRepository")
 */

class TypeDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $name;

  /**
  * @ODM\ReferenceOne(targetDocument="TypeDocument", simple=true)
  */
  private $parentType;

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
   * Get the value of subTypes
   */ 
  public function getSubTypes()
  {
    return $this->subTypes;
  }

  /**
   * Set the value of subTypes
   *
   * @return  self
   */ 
  public function setSubTypes($subTypes)
  {
    $this->subTypes = $subTypes;

    return $this;
  }

  /**
   * Get the value of parentType
   */ 
  public function getParentType()
  {
    return $this->parentType;
  }

  /**
   * Set the value of parentType
   *
   * @return  self
   */ 
  public function setParentType($parentType)
  {
    $this->parentType = $parentType;

    return $this;
  }
}