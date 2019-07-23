<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class QuestionDocument
{
  /** @ODM\Id */
  protected $id;
 
  /** @ODM\Field(type="string") */
  protected $referId;

  /** @ODM\Field(type="string") */
  private $content;

  /**
  * @ODM\ReferenceOne(targetDocument="\Test\Documents\Question\TypeDocument", simple=true)
  */
  private $type;

  /**
  * @ODM\ReferenceOne(targetDocument="\Test\Documents\Question\SourceDocument", simple=true)
  */
  private $source;

  /** @ODM\Field(type="int") */
  private $numberSubQuestion;

  /** @ODM\Field(type="int") */
  private $numberCorrectSubQuestion;

  /** @ODM\Field(type="float") */
  private $mark;

  /** @ODM\Field(type="float") */
  private $candidateMark;

  /** @ODM\Field(type="bool") */
  private $isScored;
  
  /** @ODM\Field(type="string") */
  private $comment;
  
  /** @ODM\EmbedMany(targetDocument="\Test\Documents\Question\SubQuestionDocument") */
  private $subQuestions;
  
  public function __construct()
  {
    $this->subQuestions = new ArrayCollection();
    $this->isScored = false;
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
   * Get the value of source
   */ 
  public function getSource()
  {
    return $this->source;
  }

  /**
   * Set the value of source
   *
   * @return  self
   */ 
  public function setSource($source)
  {
    $this->source = $source;

    return $this;
  }

  /**
   * Get the value of type
   */ 
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set the value of type
   *
   * @return  self
   */ 
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }

    /**
   * Get the value of questions
   */ 
  public function getSubQuestions()
  {
    return $this->subQuestions;
  }

  /**
   * Set the value of questions
   *
   * @return  self
   */ 
  public function setSubQuestions($subQuestions)
  {
    $this->subQuestions = $subQuestions;
    $this->numberSubQuestion = $this->subQuestions->count();
    
    return $this;
  }

  public function addSubQuestion($subQuestion){
    $this->subQuestions->add($subQuestion);
    $this->numberSubQuestion = $this->subQuestions->count();
  }

  /**
   * Set the value of numberOfSubQuestion
   *
   * @return  self
   */ 
  public function setNumberSubQuestion($number)
  {
    
    $this->numberSubQuestion = $number;
    return $this;
  }

  /**
   * Get the value of numberOfSubQuestion
   *
   */ 
  public function getNumberSubQuestion()
  {
    return $this->numberSubQuestion;
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
   * Get the value of numberCorrectSubQuestion
   */ 
  public function getNumberCorrectSubQuestion()
  {
    return $this->numberCorrectSubQuestion;
  }

  /**
   * Set the value of numberCorrectSubQuestion
   *
   * @return  self
   */ 
  public function setNumberCorrectSubQuestion($numberCorrectSubQuestion)
  {
    $this->numberCorrectSubQuestion = $numberCorrectSubQuestion;

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
   * Get the value of referId
   */ 
  public function getReferId()
  {
    return $this->referId;
  }

  /**
   * Set the value of referId
   *
   * @return  self
   */ 
  public function setReferId($referId)
  {
    $this->referId = $referId;

    return $this;
  }

  /**
   * Get the value of isScored
   */ 
  public function getIsScored()
  {
    return $this->isScored;
  }

  /**
   * Set the value of isScored
   *
   * @return  self
   */ 
  public function setIsScored($isScored)
  {
    $this->isScored = $isScored;

    return $this;
  }
}