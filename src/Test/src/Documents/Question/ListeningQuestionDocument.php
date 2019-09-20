<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(repositoryClass="\Test\Repositories\ListeningQuestionRepository")
 */

class ListeningQuestionDocument extends HasSubQuestionDocument
{
  
  /** @ODM\Field(type="string") */
  private $path;

  /** @ODM\Field(type="int") */
  private $repeat;

  /** @ODM\Field(type="int") */
  private $duration;

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
}