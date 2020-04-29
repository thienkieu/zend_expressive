<?php
namespace ODMAuth\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="oauth_mapping_function", repositoryClass=ODMAuth\Repositories\PermissionRepository::class)
 */

class MappingFunctionDocument
{ 
  /** @ODM\Id(type="string") */
  protected $id;

  /** @ODM\Field(type="string") */
  private $businessName;

  /** @ODM\Field(type="hash") */
  private $codeFunctions;

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
   * Get the value of businessName
   */ 
  public function getBusinessName()
  {
    return $this->businessName;
  }

  /**
   * Set the value of businessName
   *
   * @return  self
   */ 
  public function setBusinessName($businessName)
  {
    $this->businessName = $businessName;

    return $this;
  }

  /**
   * Get the value of codeFunctions
   */ 
  public function getCodeFunctions()
  {
    return $this->codeFunctions;
  }

  /**
   * Set the value of codeFunctions
   *
   * @return  self
   */ 
  public function setCodeFunctions($codeFunctions)
  {
    $this->codeFunctions = $codeFunctions;

    return $this;
  }
}