<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document
 */

class ReadingSectionDocument extends SectionDocument
{ 
  /** @ODM\EmbedMany(targetDocument="QuestionDocument") */
  private $questions;
  
  public function __construct()
  {
    $this->questions = new ArrayCollection();
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