<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="questions", repositoryClass=Test\Repositories\QuestionRepository::class)
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("documentType")
 * @ODM\DiscriminatorMap({"reading"=Test\Documents\Question\ReadingQuestionDocument::class, "listening"=Test\Documents\Question\ListeningQuestionDocument::class, "writing"=Test\Documents\Question\WritingQuestionDocument::class, "nonSub"=Test\Documents\Question\NonSubQuestionDocument::class, "verbal"=Test\Documents\Question\VerbalQuestionDocument::class, "normal"=Test\Documents\Question\QuestionDocument::class})
 */

class QuestionDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $content;

  /** @ODM\Field(type="int") */
  protected $order;

  /**
  * @ODM\ReferenceOne(targetDocument=Test\Documents\Question\TypeDocument::class, storeAs="id")
  */
  protected $type;

  /**
  * @ODM\ReferenceOne(targetDocument=Test\Documents\Question\PlatformDocument::class, storeAs="id")
  */
  protected $platform;

  /** @ODM\Field(type="string") */
  protected $platformId;

  /** @ODM\Field(type="string") */
  protected $typeId;

  /** @ODM\Field(type="string") */
  protected $parentTypeId;

  /**
  * @ODM\ReferenceOne(targetDocument=Test\Documents\Question\SourceDocument::class, storeAs="id")
  */
  protected $source;

  /**
  * @ODM\ReferenceOne(targetDocument=ODMAuth\Documents\UserDocument::class, storeAs="id")
  */
  protected $user;
  
  /** @ODM\Field(type="float") */
  protected $mark;
  
  /** @ODM\Field(type="date") */
  protected $createDate;
  
  
  public function __construct()
  {
    $this->createDate = new \DateTime();    
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
   * Get the value of parentTypeId
   */ 
  public function getParentTypeId()
  {
    return $this->parentTypeId;
  }

  /**
   * Set the value of parentTypeId
   *
   * @return  self
   */ 
  public function setParentTypeId($parentTypeId)
  {
    $this->parentTypeId = $parentTypeId;

    return $this;
  }

  /**
   * Get the value of platform
   */ 
  public function getPlatform()
  {
    return $this->platform;
  }

  /**
   * Set the value of platform
   *
   * @return  self
   */ 
  public function setPlatform($platform)
  {
    $this->platform = $platform;

    return $this;
  }

  /**
   * Get the value of platformId
   */ 
  public function getPlatformId()
  {
    return $this->platformId;
  }

  /**
   * Set the value of platformId
   *
   * @return  self
   */ 
  public function setPlatformId($platformId)
  {
    $this->platformId = $platformId;

    return $this;
  }

  /**
   * Get the value of user
   */ 
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Set the value of user
   *
   * @return  self
   */ 
  public function setUser($user)
  {
    $this->user = $user;

    return $this;
  }
}