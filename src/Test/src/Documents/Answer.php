<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */

class Answer
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\Field(type="boolean") */
  private $isCorrect;

  public function setId($value){
      $this->id = $value;
  }

  public function setContent($value){
    $this->content = $value;
  }

  public function setIsCorrect($value){
    $this->isCorrect = $value;
  }

}