<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="test_template", repositoryClass="\Test\Repositories\TestTemplateRepository")
 */

class TestTemplateDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $title;

  /** @ODM\Field(type="bool") */
  protected $isDefault;

  /** @ODM\Field(type="date") */
  protected $createDate;

  /** @ODM\EmbedMany(targetDocument="SectionDocument") */
  private $sections;
  
  public function __construct()
  {
    $this->sections = new ArrayCollection();
    $this->createDate = new \DateTime();
  }
  
  /**
   * Get the value of title
   */ 
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set the value of title
   *
   * @return  self
   */ 
  public function setTitle($title)
  {
    $this->title = $title;

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

  /**
   * Get the value of sections
   */ 
  public function getSections()
  {
    return $this->sections;
  }

  /**
   * Set the value of sections
   *
   * @return  self
   */ 
  public function setSections($sections)
  {
    $this->sections = $sections;

    return $this;
  }

  /**
   * Get the value of isDefault
   */ 
  public function getIsDefault()
  {
    return $this->isDefault;
  }

  /**
   * Set the value of isDefault
   *
   * @return  self
   */ 
  public function setIsDefault($isDefault)
  {
    $this->isDefault = $isDefault;

    return $this;
  }
}