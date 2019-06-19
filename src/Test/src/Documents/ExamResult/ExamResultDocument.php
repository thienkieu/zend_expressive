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

  /** @ODM\Field(type="int") */
  protected $time;

  /** @ODM\Field(type="string") */
  protected $title;

   /** @ODM\Field(type="date") */
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
}