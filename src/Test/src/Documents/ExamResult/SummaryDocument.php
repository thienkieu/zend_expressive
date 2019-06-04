<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="exam_result_summary")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"english"="EnglishExamResultSummaryDocument", "testarchitect"="TestArchitectExamResultSummaryDocument", "oldEnglish"="OldEnglishExamResultSummaryDocument"})
 */

class ExamResultSummaryDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $examId;

  /** @ODM\Field(type="string") */
  protected $candidateId;
  
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
}