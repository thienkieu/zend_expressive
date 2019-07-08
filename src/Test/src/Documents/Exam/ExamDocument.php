<?php
namespace Test\Documents\Exam;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="exam", repositoryClass="\Test\Repositories\ExamRepository")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 */

class ExamDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="int") */
  protected $time;

  /** @ODM\Field(type="string") */
  protected $title;

  /** @ODM\Field(type="date") */
  protected $startDate;
  
  /** @ODM\EmbedMany(targetDocument="CandidateDocument") */
  private $candidates;
  
  /** @ODM\EmbedOne(discriminatorMap={
   *     "withSection"="\Test\Documents\ExamResult\TestWithSectionDocument",
   *     "normal"="\Test\Documents\ExamResult\BaseTestDocument",
   * }) 
   */
  private $test;

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
   * Get "withSection"="\Test\Documents\ExamResult\TestWithSectionDocument",
   */ 
  public function getTest()
  {
    return $this->test;
  }

  /**
   * Set "withSection"="\Test\Documents\ExamResult\TestWithSectionDocument",
   *
   * @return  self
   */ 
  public function setTest($test)
  {
    $this->test = $test;

    return $this;
  }
}