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

  /** @ODM\EmbedMany(targetDocument="SubTypeDocument") */
  private $subTypes;

  public function __construct()
  {
    $this->subTypes = new ArrayCollection();
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
}