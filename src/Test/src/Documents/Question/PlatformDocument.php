<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="platform", repositoryClass=Test\Repositories\PlatformRepository::class)
 */

class PlatformDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $name;

  /**
  * @ODM\ReferenceOne(targetDocument=Test\Documents\Question\PlatformDocument::class, storeAs="id")
  */
  private $parentPlatform;
  
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
   * Get the value of parentPlatform
   */ 
  public function getParentPlatform()
  {
    return $this->parentPlatform;
  }

  /**
   * Set the value of parentPlatform
   *
   * @return  self
   */ 
  public function setParentPlatform($parentPlatform)
  {
    $this->parentPlatform = $parentPlatform;

    return $this;
  }

}