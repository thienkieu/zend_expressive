<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document
 */

class NormalTestDocument extends BaseTestDocument
{
  /** @ODM\EmbedMany(discriminatorMap={
   *     "reading"="ReadingSectionDocument",
   *     "writing"="WritingSectionDocument",
   *     "listening"="ListeningSectionDocument"
   *   }) */
  private $sections;

  

  /**
   * Get "reading"="ReadingSectionDocument",
   */ 
  public function getSections()
  {
    return $this->sections;
  }

  /**
   * Set "reading"="ReadingSectionDocument",
   *
   * @return  self
   */ 
  public function setSections($sections)
  {
    $this->sections = $sections;

    return $this;
  }

  /**
   * Add
   *
   * @return  self
   */ 
  public function addSection($section)
  {
    $this->sections->add($section);

    return $this;
  }
}