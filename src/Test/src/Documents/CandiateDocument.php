<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document
 */

class ExamDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $examId;

  /** @ODM\Field(type="string") */
  protected $candidateId;
  
  /** @ODM\Field(type="string") */
  protected $crmId;
  
  /** @ODM\Field(type="string") */
  protected $crmType;

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
   * Get the value of crmType
   */ 
  public function getCrmType()
  {
    return $this->crmType;
  }

  /**
   * Set the value of crmType
   *
   * @return  self
   */ 
  public function setCrmType($crmType)
  {
    $this->crmType = $crmType;

    return $this;
  }

  /**
   * Get the value of crmId
   */ 
  public function getCrmId()
  {
    return $this->crmId;
  }

  /**
   * Set the value of crmId
   *
   * @return  self
   */ 
  public function setCrmId($crmId)
  {
    $this->crmId = $crmId;

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