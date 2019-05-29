<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */
class QuestionGroupDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $title;

  /** @ODM\Field(type="string") */
  private $description;

  /** @ODM\EmbedMany(discriminatorMap={
   *     "reading"="ReadingSectionDocument",
   *     "writing"="WritingSectionDocument",
   *     "listening"="ListeningSectionDocument"
   *   }) */
  private $sections;

  public function __construct()
  {
    $this->sections = new ArrayCollection();
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
   * Get 
   */ 
  public function getSections()
  {
    return $this->sections;
  }

  /**
   * Set
   *
   * @return  self
   */ 
  public function setSections($sections)
  {
    $this->sections = $sections;

    return $this;
  }

  /**
   * Add
   *
   * @return  self
   */ 
  public function AddSection($section)
  {
    $this->sections->add($section);

    return $this;
  }

  /**
   * Get the value of description
   */ 
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set the value of description
   *
   * @return  self
   */ 
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }
}