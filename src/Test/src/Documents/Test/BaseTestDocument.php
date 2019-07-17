<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="test", repositoryClass="\Test\Repositories\BaseTestRepository")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"testGroup"="TestWithSectionDocument", "normal"="NormalTestDocument", "crm"="CRMTestDocument"})
 */

class BaseTestDocument
{
  /** @ODM\Id(strategy="NONE", type="string")  */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $title;

  /** @ODM\Field(type="date") */
  protected $createDate;

  public function __construct()
  {
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
}