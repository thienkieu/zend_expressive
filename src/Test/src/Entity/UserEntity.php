<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="user")
 */
class UserEntity 
{
  /** 
    * @Id
    * @Column(type="string")
    * @var string
    */
  protected $id;

  /**
    * @Column(type="string")
    * @var string
    */
  protected $name;

  /**
    * @Column(type="string")
    * @var string
    */
  protected $email;

  /**
    * @Column(type="string")
    * @var string
    */
  protected $skype;

  public function setId($value){
      $this->id = $value;
  }

  public function setEmail($value){
    $this->email = $value;
  }

  public function setName($value){
    $this->name = $value;
  }

  public function setSkype($value){
    $this->skype = $value;
  }
}