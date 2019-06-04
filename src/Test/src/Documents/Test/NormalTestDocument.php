<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document
 */

class NormalTestDocument extends BaseTestDocument
{
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