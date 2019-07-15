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

   /** @ODM\Field(type="bool") */
   protected $isScored;

  /** @ODM\Field(type="float") */
  protected $mark;

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
}