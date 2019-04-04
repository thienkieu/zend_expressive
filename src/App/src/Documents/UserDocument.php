<?php
namespace App\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class UserDocument
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $title;

  /** @ODM\Field(type="string") */
  private $body;

  public function setId($value){
      $this->id = $value;
  }

  public function setTitle($value){
    $this->title = $value;
  }

  public function setBody($value){
    $this->body = $value;
  }

}