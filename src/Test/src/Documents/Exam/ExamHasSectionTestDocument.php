<?php
namespace Test\Documents\Exam;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(repositoryClass="\Test\Repositories\ExamWithSectionRepository")
 */

class ExamHasSectionTestDocument extends ExamDocument
{
  /** @ODM\EmbedOne(targetDocument="\Test\Documents\Test\TestWithSectionDocument") */
  private $test;

  /** @ODM\EmbedMany(targetDocument="CandidateDocument") */
  private $candidates;
  
  public function __construct()
  {
    $this->candidates = new ArrayCollection();
  }

  /**
   * Get the value of test
   */ 
  public function getTest()
  {
    return $this->test;
  }

  /**
   * Set the value of test
   *
   * @return  self
   */ 
  public function setTest($test)
  {
    $this->test = $test;

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


  /** @ODM\Field(type="date") */
  protected $startDate;


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