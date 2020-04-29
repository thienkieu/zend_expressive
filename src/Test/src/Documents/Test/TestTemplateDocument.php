<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="test_template", repositoryClass=Test\Repositories\TestTemplateRepository::class)
 */

class TestTemplateDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $title;

  /** @ODM\Field(type="string") */
  protected $path;

  /** @ODM\Field(type="bool") */
  protected $isDefault;

  /** @ODM\Field(type="date") */
  protected $createDate;

  /** @ODM\EmbedMany(targetDocument=Test\Documents\Test\SectionDocument::class) */
  private $sections;
  
  /**
  * @ODM\ReferenceOne(targetDocument=ODMAuth\Documents\UserDocument::class, storeAs="id")
  */
  private $user;

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

   /**
   * Add the value of sections
   *
   * @return  self
   */ 
  public function addSection($section)
  {
    $this->sections->add($section);

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
   * Get the value of path
   */ 
  public function getPath()
  {
    return $this->path;
  }

  /**
   * Set the value of path
   *
   * @return  self
   */ 
  public function setPath($path)
  {
    $this->path = $path;

    return $this;
  }
}
