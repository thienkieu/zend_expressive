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

  /** @ODM\Field(type="string") */
  protected $referId;

  /** @ODM\Field(type="int") */
  protected $time;

  /** @ODM\Field(type="string") */
  protected $title;

  /** @ODM\Field(type="timestamp") */
  protected $startDate;
  
  /** @ODM\EmbedMany(targetDocument="CandidateDocument") */
  private $candidates;
  
  public function __construct()
  {
    $this->candidates = new ArrayCollection();
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
   * Get the value of candidates
   */ 
  public function getCandidates()
  {
    return $this->candidates;
  }

  /**
   * Set the value of candidates
   *
   * @return  self
   */ 
  public function setCandidates($candidates)
  {
    $this->candidates = $candidates;

    return $this;
  }

  /**
   * Add the value of candidates
   *
   * @return  self
   */ 
  public function addCandidate($candidate)
  {
    $this->candidates->add($candidate);

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

  /**
   * Get the value of referId
   */ 
  public function getReferId()
  {
    return $this->referId;
  }

  /**
   * Set the value of referId
   *
   * @return  self
   */ 
  public function setReferId($referId)
  {
    $this->referId = $referId;

    return $this;
  }
}