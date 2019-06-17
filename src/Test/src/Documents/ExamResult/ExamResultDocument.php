<?php
namespace Test\Documents\ExamResult;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="exam_result")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"testWithSection"="ExamHasSectionTestDocument", "normal"="ExamNormalDocument"})
 */

class ExamResultDocument
{
  /** @ODM\Id */
  protected $id;

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

  /**
   * Get the value of candidateId
   */ 
  public function getCandidateId()
  {
    return $this->candidateId;
  }

  /**
   * Set the value of candidateId
   *
   * @return  self
   */ 
  public function setCandidateId($candidateId)
  {
    $this->candidateId = $candidateId;

    return $this;
  }
}