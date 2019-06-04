<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="exam_result_summary")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"english"="EnglishExamResultSummaryDocument", "testarchitect"="TestArchitectExamResultSummaryDocument", "oldEnglish"="OldEnglishExamResultSummaryDocument"})
 */

class ExamDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $testId;

  /** @ODM\EmbedOne(targetDocument="Test\BaseTestDocument") */
  private $test;

  /** @ODM\Field(type="int") */
  protected $time;
  
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

  /**
   * Get the value of testId
   */ 
  public function getTestId()
  {
    return $this->testId;
  }

  /**
   * Set the value of testId
   *
   * @return  self
   */ 
  public function setTestId($testId)
  {
    $this->testId = $testId;

    return $this;
  }

  /**
   * Get the value of time
   */ 
  public function getTime()
  {
    return $this->time;
  }

  /**
   * Set the value of time
   *
   * @return  self
   */ 
  public function setTime($time)
  {
    $this->time = $time;

    return $this;
  }
}