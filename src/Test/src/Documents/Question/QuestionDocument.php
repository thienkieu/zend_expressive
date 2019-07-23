<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="questions", repositoryClass="\Test\Repositories\QuestionRepository")
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

  /**
  * @ODM\ReferenceOne(targetDocument="TypeDocument", simple=true)
  */
  private $type;

  /**
  * @ODM\ReferenceOne(targetDocument="SourceDocument", simple=true)
  */
  private $source;

  /** @ODM\Field(type="int") */
  private $numberSubQuestion;
  
  /** @ODM\Field(type="float") */
  private $mark;
  
  /** @ODM\Field(type="date") */
  protected $createDate;
  
  /** @ODM\EmbedMany(targetDocument="SubQuestionDocument") */
  private $subQuestions;
  
  public function __construct()
  {
    $this->subQuestions = new ArrayCollection();
    $this->createDate = new \DateTime();
    $this->mark = \Config\AppConstant::DefaultSubQuestionMark;
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
   * Get the value of createDate
   */ 
  public function getCreateDate()
  {
    return $this->createDate;
  }

  /**
   * Set the value of createDate
   *
   * @return  self
   */ 
  public function setCreateDate($createDate)
  {
    $this->createDate = $createDate;

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