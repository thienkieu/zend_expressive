<?php
namespace Test\Documents\Exam;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="exam")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"testWithSection"="ExamHasSectionTestDocument", "normal"="ExamNormalDocument"})
 */

class ExamDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="int") */
  protected $time;

  /** @ODM\Field(type="string") */
  protected $title;

  /** @ODM\Field(type="timestamp") */
  protected $startDate;
  
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

  /**
   * Get the value of startDate
   */ 
  public function getStartDate()
  {
    return $this->startDate;
  }

  /**
   * Set the value of startDate
   *
   * @return  self
   */ 
  public function setStartDate($startDate)
  {
    $this->startDate = $startDate;

    return $this;
  }

  /**
   * Get the value of title
   */ 
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set the value of title
   *
   * @return  self
   */ 
  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }
}