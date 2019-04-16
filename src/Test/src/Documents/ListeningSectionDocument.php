<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document
 */

class ListeningSectionDocument extends SectionDocument
{
  
  /** @ODM\Field(type="string") */
  private $path;

  /** @ODM\Field(type="int") */
  private $repeat;

  /** @ODM\EmbedMany(targetDocument="QuestionDocument") */
  private $questions;
  
  public function __construct()
  {
    $this->questions = new ArrayCollection();
  }

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
   * Get the value of questions
   */ 
  public function getQuestions()
  {
    return $this->questions;
  }

  /**
   * Set the value of questions
   *
   * @return  self
   */ 
  public function setQuestions($questions)
  {
    $this->questions = $questions;

    return $this;
  }

  public function addQuestion($question){
    $this->questions->add($question);
  }
}