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
  * @ODM\ReferenceOne(targetDocument=Test\Documents\Question\TypeDocument::class, storeAs="id")
  */
  private $type;

  /**
  * @ODM\ReferenceOne(targetDocument=Test\Documents\Question\PlatformDocument::class, storeAs="id")
  */
  protected $platform;

  /** @ODM\Field(type="string") */
  protected $platformId;

  /**
  * @ODM\ReferenceOne(targetDocument=Test\Documents\Question\SourceDocument::class, storeAs="id")
  */
  private $source;

  /**
  * @ODM\ReferenceOne(targetDocument=ODMAuth\Documents\UserDocument::class, storeAs="id")
  */
  private $user;

  /** @ODM\Field(type="float") */
  private $mark;

  /** @ODM\Field(type="float") */
  private $candidateMark;

  /** @ODM\Field(type="bool") */
  private $isScored;
  
  /** @ODM\Field(type="string") */
  private $comment;
  
  public function __construct()
  {
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
}