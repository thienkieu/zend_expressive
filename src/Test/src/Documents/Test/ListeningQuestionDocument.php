<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class ListeningQuestionDocument extends HasSubQuestionDocument
{
  
  /** @ODM\Field(type="string") */
  private $path;

  /** @ODM\Field(type="int") */
  private $repeat;

  /** @ODM\Field(type="int") */
  private $duration;

  /** @ODM\Field(type="int") */
  private $latestClick;

  /** @ODM\Field(type="bool") */
  private $isFinished;

  /** @ODM\Field(type="int") */
  private $latestDisconnect;

  /**
   * Get the value of path
   */ 
  public function getPath()
  {
    return $this->path;
  }

  /**
   * Set the value of path
   *
   * @return  self
   */ 
  public function setPath($path)
  {
    $this->path = $path;

    return $this;
  }

  /**
   * Get the value of repeat
   */ 
  public function getRepeat()
  {
    return $this->repeat;
  }

  /**
   * Set the value of repeat
   *
   * @return  self
   */ 
  public function setRepeat($repeat)
  {
    $this->repeat = $repeat;

    return $this;
  }

  /**
   * Get the value of duration
   */ 
  public function getDuration()
  {
    return $this->duration;
  }

  /**
   * Set the value of duration
   *
   * @return  self
   */ 
  public function setDuration($duration)
  {
    $this->duration = $duration;

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
   * Get the value of isFinished
   */ 
  public function getIsFinished()
  {
    return $this->isFinished;
  }

  /**
   * Set the value of isFinished
   *
   * @return  self
   */ 
  public function setIsFinished($isFinished)
  {
    $this->isFinished = $isFinished;

    return $this;
  }

  /**
   * Get the value of latestClick
   */ 
  public function getLatestClick()
  {
    return $this->latestClick;
  }

  /**
   * Set the value of latestClick
   *
   * @return  self
   */ 
  public function setLatestClick($latestClick)
  {
    $this->latestClick = $latestClick;

    return $this;
  }
}