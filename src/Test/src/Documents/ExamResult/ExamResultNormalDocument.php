<?php
namespace Test\Documents\ExamResult;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document
 */

class ExamResultNormalDocument extends ExamResultDocument
{
  /** @ODM\EmbedOne(targetDocument=Test\Documents\Test\TestWithSectionDocument::class) */
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