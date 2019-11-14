<?php
namespace Test\Documents\Exam;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/** 
 * @ODM\EmbeddedDocument
 */

class ExamResultSummaryDocument
{
  /** @ODM\Field(type="string") */
  protected $name;

  /** @ODM\Field(type="string") */
  protected $type;

  // TOIEC, LOGIGER
  /** @ODM\Field(type="string") */
  protected $businessType;

  /** @ODM\Field(type="hash") */
  protected $comments;

  /** @ODM\Field(type="bool") */
  protected $isScored;

  /** @ODM\Field(type="date") */
  protected $toeicExpirationDate;

  /** @ODM\Field(type="bool") */
  protected $isToeic;

  /** @ODM\Field(type="float") */
  protected $mark;

  /** @ODM\Field(type="float") */
  protected $candidateMark;

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
   * Get the value of mark
   */ 
  public function getMark()
  {
    return $this->mark;
  }

  /**
   * Set the value of mark
   *
   * @return  self
   */ 
  public function setMark($mark)
  {
    $this->mark = $mark;

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
    * Get the value of isScored
    */ 
   public function getIsScored()
   {
      return $this->isScored;
   }

   /**
    * Set the value of isScored
    *
    * @return  self
    */ 
   public function setIsScored($isScored)
   {
      $this->isScored = $isScored;

      return $this;
   }

  /**
   * Get the value of candidateMark
   */ 
  public function getCandidateMark()
  {
    return $this->candidateMark;
  }

  /**
   * Set the value of candidateMark
   *
   * @return  self
   */ 
  public function setCandidateMark($candidateMark)
  {
    $this->candidateMark = $candidateMark;

    return $this;
  }

  /**
   * Get the value of businessType
   */ 
  public function getBusinessType()
  {
    return $this->businessType;
  }

  /**
   * Set the value of businessType
   *
   * @return  self
   */ 
  public function setBusinessType($businessType)
  {
    $this->businessType = $businessType;

    return $this;
  }

  /**
   * Get the value of comments
   */ 
  public function getComments()
  {
    return $this->comments;
  }

  /**
   * Set the value of comments
   *
   * @return  self
   */ 
  public function setComments($comments)
  {
    $this->comments = $comments;

    return $this;
  }

  /**
   * Get the value of isToeic
   */ 
  public function getIsToeic()
  {
    return $this->isToeic;
  }

  /**
   * Set the value of isToeic
   *
   * @return  self
   */ 
  public function setIsToeic($isToeic)
  {
    $this->isToeic = $isToeic;

    return $this;
  }

  /**
   * Get the value of toeicExpirationDate
   */ 
  public function getToeicExpirationDate()
  {
    return $this->toeicExpirationDate;
  }

  /**
   * Set the value of toeicExpirationDate
   *
   * @return  self
   */ 
  public function setToeicExpirationDate($toeicExpirationDate)
  {
    $this->toeicExpirationDate = $toeicExpirationDate;

    return $this;
  }
}