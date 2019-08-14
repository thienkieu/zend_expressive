<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="questions", repositoryClass="\Test\Repositories\QuestionRepository")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("documentType")
 * @ODM\DiscriminatorMap({"reading"="ReadingQuestionDocument", "listening"="ListeningQuestionDocument", "writing"="WritingQuestionDocument", "nonSub"="NonSubQuestionDocument", "verbal"="VerbalQuestionDocument", "normal"="QuestionDocument"})
 */

class QuestionDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\Field(type="int") */
  private $order;

  /**
  * @ODM\ReferenceOne(targetDocument="TypeDocument", simple=true)
  */
  private $type;

  /** @ODM\Field(type="string") */
  private $typeId;

  /** @ODM\Field(type="string") */
  private $subTypeId;

  /**
  * @ODM\ReferenceOne(targetDocument="SourceDocument", simple=true)
  */
  private $source;
  
  /** @ODM\Field(type="float") */
  private $mark;
  
  /** @ODM\Field(type="date") */
  protected $createDate;
  
  
  public function __construct()
  {
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
   * Get the value of typeId
   */ 
  public function getTypeId()
  {
    return $this->typeId;
  }

  /**
   * Set the value of typeId
   *
   * @return  self
   */ 
  public function setTypeId($typeId)
  {
    $this->typeId = $typeId;

    return $this;
  }

  /**
   * Get the value of subTypeId
   */ 
  public function getSubTypeId()
  {
    return $this->subTypeId;
  }

  /**
   * Set the value of subTypeId
   *
   * @return  self
   */ 
  public function setSubTypeId($subTypeId)
  {
    $this->subTypeId = $subTypeId;

    return $this;
  }
}