<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="sections")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"reading"="ReadingSectionDocument", "listening"="ListeningSectionDocument", "writing"="WritingSectionDocument"})
 */

class SectionDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

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
   * Get the value of content
   */ 
  public function getContent()
  {
    return $this->content;
  }

  /**
   * Set the value of content
   *
   * @return  self
   */ 
  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }
  
}