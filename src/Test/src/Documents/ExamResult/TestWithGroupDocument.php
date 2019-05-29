<?php
namespace Test\Documents\ExamResult;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document
 */

class TestWithGroupDocument extends BaseTestDocument
{
  /** @ODM\EmbedMany(targetDocument="SectionGroupDocument") */
  private $sectionGroups;
  
  public function __construct()
  {
    $this->sectionGroups = new ArrayCollection();
  }

  /**
   * Get the value of sectionGroups
   */ 
  public function getSectionGroups()
  {
    return $this->sectionGroups;
  }

  /**
   * Set the value of sectionGroups
   *
   * @return  self
   */ 
  public function setSectionGroups($sectionGroups)
  {
    $this->sectionGroups = $sectionGroups;

    return $this;
  }

   /**
   * Add the value of sectionGroups
   *
   * @return  self
   */ 
  public function addSectionGroup($sectionGroup)
  {
    $this->sectionGroups->add($sectionGroup);

    return $this;
  }
}