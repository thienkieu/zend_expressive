<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="search_config")
 */

class SearchConfigDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $objectType;

  /** @ODM\Field(type="hash") */
  private $searchPaths;
  
  public function __construct()
  {
    $this->searchPaths = [];
  }

  /**
   * Get the value of searchPaths
   */ 
  public function getSearchPaths()
  {
    return $this->searchPaths;
  }

  /**
   * Set the value of searchPaths
   *
   * @return  self
   */ 
  public function setSearchPaths($searchPaths)
  {
    $this->searchPaths = $searchPaths;

    return $this;
  }

  /**
   * Get the value of objectType
   */ 
  public function getObjectType()
  {
    return $this->objectType;
  }

  /**
   * Set the value of objectType
   *
   * @return  self
   */ 
  public function setObjectType($objectType)
  {
    $this->objectType = $objectType;

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
}