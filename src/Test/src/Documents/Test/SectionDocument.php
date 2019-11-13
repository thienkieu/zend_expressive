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

  /** @ODM\Field(type="boolean") */
  private $isToeic;

  /** @ODM\Field(type="float") */
  private $mark;

  /** @ODM\Field(type="float") */
  private $candidateMark;

  /** @ODM\Field(type="string") */
  private $comment;

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

  /**
   * Get the value of candidateMark
   */ 
  public function getCandidateMark()
  {
    return $this->candidateMark;
  }

  /**
   * Set the value of candidateMark
   *
   * @return  self
   */ 
  public function setCandidateMark($candidateMark)
  {
    $this->candidateMark = $candidateMark;

    return $this;
  }

  /**
   * Get the value of comment
   */ 
  public function getComment()
  {
    return $this->comment;
  }

  /**
   * Set the value of comment
   *
   * @return  self
   */ 
  public function setComment($comment)
  {
    $this->comment = $comment;

    return $this;
  }

  /**
   * Get the value of isToeic
   */ 
  public function getIsToeic()
  {
    return $this->isToeic;
  }

  /**
   * Set the value of isToeic
   *
   * @return  self
   */ 
  public function setIsToeic($isToeic)
  {
    $this->isToeic = $isToeic;

    return $this;
  }
}