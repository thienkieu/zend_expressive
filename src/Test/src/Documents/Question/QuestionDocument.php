<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="sections")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("documentType")
 * @ODM\DiscriminatorMap({"reading"="ReadingQuestionDocument", "listening"="ListeningQuestionDocument", "writing"="WritingQuestionDocument"})
 */

class QuestionDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\Field(type="string") */
  private $type;

  /** @ODM\Field(type="string") */
  private $source;

  /** @ODM\Field(type="string") */
  private $subType;

  /** @ODM\EmbedMany(targetDocument="SubQuestionDocument") */
  private $subQuestions;
  
  public function __construct()
  {
    $this->subQuestions = new ArrayCollection();
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
   * Get the value of subType
   */ 
  public function getSubType()
  {
    return $this->subType;
  }

  /**
   * Set the value of subType
   *
   * @return  self
   */ 
  public function setSubType($subType)
  {
    $this->subType = $subType;

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

    return $this;
  }

  public function addSubQuestion($subQuestion){
    $this->subQuestions->add($subQuestion);
  }
}