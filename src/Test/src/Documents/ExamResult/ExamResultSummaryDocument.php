<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/** 
 * @ODM\EmbeddedDocument
 */

class ExamResultSummaryDocument
{

  /** @ODM\EmbedOne(targetDocument="CandidateDocument") */
  private $candidate;
  
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