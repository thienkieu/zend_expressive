<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class QuestionDocument
{
  /** @ODM\Id */
  private $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\EmbedMany(targetDocument="AnswerDocument") */
  private $answers;

  public function __construct()
  {
    $this->answers = new ArrayCollection();
  }

  public function addAnswer($answer){
    $this->answers->add($answer);
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
   * Get the value of content
   */ 
  public function getContent()
  {
    return $this->content;
  }

  /**
   * Set the value of content
   *
   * @return  self
   */ 
  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  /**
   * Get the value of answers
   */ 
  public function getAnswers()
  {
    return $this->answers;
  }

  /**
   * Set the value of answers
   *
   * @return  self
   */ 
  public function setAnswers($answers)
  {
    $this->answers = $answers;

    return $this;
  }
}