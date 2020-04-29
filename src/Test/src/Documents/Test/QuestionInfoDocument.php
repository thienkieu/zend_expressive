<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */
class QuestionInfoDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $generateFrom;

  /** @ODM\EmbedOne(discriminatorMap={"reading"=Test\Documents\Test\ReadingQuestionDocument::class,"writing"=Test\Documents\Test\WritingQuestionDocument::class, "listening"=Test\Documents\Test\ListeningQuestionDocument::class, "random"=Test\Documents\Test\RandomQuestionDocument::class,"verbal"=Test\Documents\Test\VerbalQuestionDocument::class,"nonsubquestion"=Test\Documents\Test\NonSubQuestionDocument::class}) */
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