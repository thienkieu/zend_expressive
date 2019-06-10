<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="test", repositoryClass="\Test\Repositories\TestWithSectionRepository")
 */

class TestWithSectionDocument extends BaseTestDocument
{
  /** @ODM\EmbedMany(targetDocument="SectionDocument") */
  private $sections;
  
  public function __construct()
  {
    $this->sections = new ArrayCollection();
  }

  /**
   * Get the value of sections
   */ 
  public function getSections()
  {
    return $this->sections;
  }

  /**
   * Set the value of sections
   *
   * @return  self
   */ 
  public function setSections($sections)
  {
    $this->sections = $sections;

    return $this;
  }

   /**
   * Add the value of sections
   *
   * @return  self
   */ 
  public function addSection($section)
  {
    $this->sections->add($section);

    return $this;
  }
}