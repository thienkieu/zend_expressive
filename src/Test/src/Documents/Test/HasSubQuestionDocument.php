<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class HasSubQuestionDocument extends QuestionDocument
{
  /** @ODM\Field(type="int") */
  private $numberSubQuestion;

  /** @ODM\Field(type="int") */
  private $numberCorrectSubQuestion;

  /** @ODM\EmbedMany(targetDocument="\Test\Documents\Question\SubQuestionDocument") */
  private $subQuestions;
  
  public function __construct()
  {
    parent::__construct();
    $this->subQuestions = new ArrayCollection();
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
}