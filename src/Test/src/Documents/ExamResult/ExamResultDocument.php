<?php
namespace Test\Documents\ExamResult;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="exam_result")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"testWithSection"="ExamResultHasSectionTestDocument", "normal"="ExamResultNormalDocument"})
 */

class ExamResultDocument
{

  /** @ODM\Id */
  protected $id;

  /** @ODM\EmbedOne(targetDocument="\Test\Documents\Exam\CandidateDocument") */
  private $candidate;

  /** @ODM\Field(type="string") */
  protected $examId;

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
   * Get the value of candidate
   */ 
  public function getCandidate()
  {
    return $this->candidate;
  }

  /**
   * Set the value of candidate
   *
   * @return  self
   */ 
  public function setCandidate($candidate)
  {
    $this->candidate = $candidate;

    return $this;
  }

  /**
   * Get the value of examId
   */ 
  public function getExamId()
  {
    return $this->examId;
  }

  /**
   * Set the value of examId
   *
   * @return  self
   */ 
  public function setExamId($examId)
  {
    $this->examId = $examId;

    return $this;
  }
}