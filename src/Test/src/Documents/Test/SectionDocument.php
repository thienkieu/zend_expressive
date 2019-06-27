<?php
namespace Test\Documents\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */
class SectionDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $name;

  /** @ODM\Field(type="string") */
  private $description;

  /** @ODM\EmbedMany(targetDocument="QuestionInfoDocument") */
  private $questions;

  public function __construct()
  {
    $this->questions = new ArrayCollection();
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
   * Add
   *
   * @return  self
   */ 
  public function AddQuestion($question)
  {
    $this->questions->add($question);

    return $this;
  }

  /**
   * Get the value of description
   */ 
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set the value of description
   *
   * @return  self
   */ 
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get the value of name
   */ 
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */ 
  public function setName($name)
  {
    $this->name = $name;

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

}