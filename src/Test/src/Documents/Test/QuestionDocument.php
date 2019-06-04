<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */
class QuestionDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $generateFrom;
  
  /** @ODM\EmbedOne(discriminatorMap={
   *     "reading"="\Test\Documents\Question\ReadingQuestionDocument",
   *     "writing"="\Test\Documents\Question\WritingQuestionDocument",
   *     "listening"="\Test\Documents\Question\ListeningQuestionDocument",
   *      "random"="\Test\Documents\Test\RandomQuestionDocument"
   *   }) */
  private $questionInfo;

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

  public function getQuestionInfo()
  {
    return $this->questionInfo;
  }

  
  public function setQuestionInfo($questionInfo)
  {
    $this->questionInfo = $questionInfo;

    return $this;
  }

  /**
   * Get the value of generateFrom
   */ 
  public function getGenerateFrom()
  {
    return $this->generateFrom;
  }

  /**
   * Set the value of generateFrom
   *
   * @return  self
   */ 
  public function setGenerateFrom($generateFrom)
  {
    $this->generateFrom = $generateFrom;

    return $this;
  }
}