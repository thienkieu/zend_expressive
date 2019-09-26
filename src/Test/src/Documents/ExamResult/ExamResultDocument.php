<?php
namespace Test\Documents\ExamResult;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;
use time;

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

  /** @ODM\EmbedOne(targetDocument="CandidateDocument") */
  private $candidate;

  /** @ODM\Field(type="string") */
  protected $examId;

  /** @ODM\Field(type="int") */
  protected $time;

  /** @ODM\Field(type="bool") */
  protected $isDone;

  /** @ODM\Field(type="string") */
  protected $title;

  /** @ODM\Field(type="string") */
  protected $examType;

   /** @ODM\Field(type="date") */
  protected $startDate;

  /** @ODM\Field(type="int") */
  protected $remainTime;
  
  /** @ODM\Field(type="int") */
  protected $latestDisconnect;
  
  /** @ODM\Field(type="int") */
  protected $latestConnectionTime;

  /** @ODM\Field(type="int") */
  protected $totalSpendingTime;

  /** @ODM\EmbedMany(targetDocument="\Test\Documents\Exam\ExamResultSummaryDocument") */
  protected $resultSummary;

  /**
   * Class constructor.
   */
  public function __construct()
  {
    $this->isDone = false;
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

  /**
   * Get the value of remainTime
   */ 
  public function getRemainTime()
  {
    return $this->remainTime;
  }

  /**
   * Set the value of remainTime
   *
   * @return  self
   */ 
  public function setRemainTime($remainTime)
  {
    $this->remainTime = $remainTime;

    return $this;
  }

  /**
   * Get the value of isDone
   */ 
  public function getIsDone()
  {
    return $this->isDone;
  }

  /**
   * Set the value of isDone
   *
   * @return  self
   */ 
  public function setIsDone($isDone)
  {
    $this->isDone = $isDone;

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

  /**
   * Get the value of examType
   */ 
  public function getExamType()
  {
    return $this->examType;
  }

  /**
   * Set the value of examType
   *
   * @return  self
   */ 
  public function setExamType($examType)
  {
    $this->examType = $examType;

    return $this;
  }

  /**
   * Get the value of latestDisconnect
   */ 
  public function getLatestDisconnect()
  {
    return $this->latestDisconnect;
  }

  /**
   * Set the value of latestDisconnect
   *
   * @return  self
   */ 
  public function setLatestDisconnect($latestDisconnect)
  {
    $this->latestDisconnect = $latestDisconnect;

    return $this;
  }

  /**
   * Get the value of latestConnectionTime
   */ 
  public function getLatestConnectionTime()
  {
    return $this->latestConnectionTime;
  }

  /**
   * Set the value of latestConnectionTime
   *
   * @return  self
   */ 
  public function setLatestConnectionTime($latestConnectionTime)
  {
    $this->latestConnectionTime = $latestConnectionTime;

    return $this;
  }

  /**
   * Get the value of totalSpendingTime
   */ 
  public function getTotalSpendingTime()
  {
    return $this->totalSpendingTime;
  }

  /**
   * Set the value of totalSpendingTime
   *
   * @return  self
   */ 
  public function setTotalSpendingTime($totalSpendingTime)
  {
    $this->totalSpendingTime = $totalSpendingTime;

    return $this;
  }
}