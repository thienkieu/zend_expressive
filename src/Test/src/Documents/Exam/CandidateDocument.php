<?php
namespace Test\Documents\Exam;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class CandidateDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $examCandidateId;

  /** @ODM\Field(type="string") */
  protected $objectId;

  /** @ODM\Field(type="string") */
  protected $type;
  
  /** @ODM\Field(type="string") */
  protected $email;
  
  /** @ODM\Field(type="string") */
  protected $name;

  /** @ODM\Field(type="string") */
  protected $pin;

  /** @ODM\Field(type="boolean") */
  protected $isPinValid = true;

  /** @ODM\EmbedMany(targetDocument=Test\Documents\Exam\ExamResultSummaryDocument::class) */
  protected $resultSummary;

  public function __construct()
  {
    $this->resultSummary = new ArrayCollection();
  }

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
   * Get the value of objectId
   */ 
  public function getObjectId()
  {
    return $this->objectId;
  }

  /**
   * Set the value of objectId
   *
   * @return  self
   */ 
  public function setObjectId($objectId)
  {
    $this->objectId = $objectId;

    return $this;
  }

  /**
   * Get the value of type
   */ 
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set the value of type
   *
   * @return  self
   */ 
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }

  /**
   * Get the value of email
   */ 
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set the value of email
   *
   * @return  self
   */ 
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get the value of name
   */ 
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */ 
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of pin
   */ 
  public function getPin()
  {
    return $this->pin;
  }

  /**
   * Set the value of pin
   *
   * @return  self
   */ 
  public function setPin($pin)
  {
    $this->pin = $pin;

    return $this;
  }

  /**
   * Get the value of isPinValid
   */ 
  public function getIsPinValid()
  {
    return $this->isPinValid;
  }

  /**
   * Set the value of isPinValid
   *
   * @return  self
   */ 
  public function setIsPinValid($isPinValid)
  {
    $this->isPinValid = $isPinValid;

    return $this;
  }

  /**
   * Get the value of examCandidateId
   */ 
  public function getExamCandidateId()
  {
    return $this->examCandidateId;
  }

  /**
   * Set the value of examCandidateId
   *
   * @return  self
   */ 
  public function setExamCandidateId($examCandidateId)
  {
    $this->examCandidateId = $examCandidateId;

    return $this;
  }

  /**
   * Get the value of resultSummary
   */ 
  public function getResultSummary()
  {
    return $this->resultSummary;
  }

  /**
   * Set the value of resultSummary
   *
   * @return  self
   */ 
  public function setResultSummary($resultSummary)
  {
    $this->resultSummary = $resultSummary;

    return $this;
  }
}