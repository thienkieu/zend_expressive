<?php
namespace Test\Documents\ExamResult;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(repositoryClass="\Test\Repositories\ExamResultHasSectionTestRepository")
 */

class ExamResultHasSectionTestDocument extends ExamResultDocument
{
  /** @ODM\EmbedOne(targetDocument="TestWithSectionDocument") */
  private $test;

  /**
   * Get the value of test
   */ 
  public function getTest()
  {
    return $this->test;
  }

  /**
   * Set the value of test
   *
   * @return  self
   */ 
  public function setTest($test)
  {
    $this->test = $test;

    return $this;
  }
}