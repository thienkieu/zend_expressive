<?php
namespace Test\Documents\ExamResult;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


class BaseTestDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  protected $title;

   /** @ODM\Field(type="string") */
   protected $referId;
  
    /**
  * @ODM\ReferenceOne(targetDocument=ODMAuth\Documents\UserDocument::class, storeAs="id")
  */
  protected $user;
  
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