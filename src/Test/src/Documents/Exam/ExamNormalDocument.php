<?php
namespace Test\Documents\Exam;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document
 */

class ExamNormalDocument extends ExamDocument
{
  /** @ODM\EmbedOne(targetDocument="\Test\Documents\Test\TestWithSectionDocument") */
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