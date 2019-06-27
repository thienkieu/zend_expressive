<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class SubQuestionDocument
{
  /** @ODM\Id */
  private $id;

  
  /** @ODM\Field(type="string") */
  private $content;
  
  /** @ODM\Field(type="int") */
  private $order;

  /** @ODM\Field(type="int") */
  private $selectedAnswer;

  /** @ODM\Field(type="float") */
  private $mark;

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

  /**
   * Get the value of order
   */ 
  public function getOrder()
  {
    return $this->order;
  }

  /**
   * Set the value of order
   *
   * @return  self
   */ 
  public function setOrder($order)
  {
    $this->order = $order;

    return $this;
  }

  /**
   * Get the value of selectedAnswer
   */ 
  public function getSelectedAnswer()
  {
    return $this->selectedAnswer;
  }

  /**
   * Set the value of selectedAnswer
   *
   * @return  self
   */ 
  public function setSelectedAnswer($selectedAnswer)
  {
    $this->selectedAnswer = $selectedAnswer;

    return $this;
  }

  /**
   * Get the value of mark
   */ 
  public function getMark()
  {
    return $this->mark;
  }

  /**
   * Set the value of mark
   *
   * @return  self
   */ 
  public function setMark($mark)
  {
    $this->mark = $mark;

    return $this;
  }
}