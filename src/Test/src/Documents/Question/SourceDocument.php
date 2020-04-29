<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="source", repositoryClass=Test\Repositories\SourceRepository::class)
 * @ODM\Index(keys={"name"="text"})
 */

class SourceDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $name;

  /** @ODM\Field(type="date") */
  protected $createDate;
  

  public function __construct()
  {
    $this->createDate = new \DateTime();    
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
}