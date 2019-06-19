<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */

class AnswerDocument
{
  /** @ODM\Id */
  private $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\Field(type="boolean") */
  private $isCorrect;

  /** @ODM\Field(type="int") */
  private $order;

  /** @ODM\Field(type="boolean") */
  private $isUserChoice;

  /**
   * Get the value of isCorrect
   */ 
  public function getIsCorrect()
  {
    return $this->isCorrect;
  }

  /**
   * Set the value of isCorrect
   *
   * @return  self
   */ 
  public function setIsCorrect($isCorrect)
  {
    $this->isCorrect = $isCorrect;

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
   * Get the value of isUserChoice
   */ 
  public function getIsUserChoice()
  {
    return $this->isUserChoice;
  }

  /**
   * Set the value of isUserChoice
   *
   * @return  self
   */ 
  public function setIsUserChoice($isUserChoice)
  {
    $this->isUserChoice = $isUserChoice;

    return $this;
  }
}