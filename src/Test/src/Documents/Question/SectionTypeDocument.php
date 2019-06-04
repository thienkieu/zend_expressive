<?php
namespace Test\Documents\Section;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="section_type")
 */

class SectionTypeDocument
{ 
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $name;

  /** @ODM\Field(type="string") */
  private $className;

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
   * Get the value of className
   */ 
  public function getClassName()
  {
    return $this->className;
  }

  /**
   * Set the value of className
   *
   * @return  self
   */ 
  public function setClassName($className)
  {
    $this->className = $className;

    return $this;
  }
}