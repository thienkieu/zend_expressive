<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */

class AnswerDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\Field(type="boolean") */
  private $isCorrect;

  

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
}