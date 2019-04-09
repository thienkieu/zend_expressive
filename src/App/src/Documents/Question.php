<?php
namespace App\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class Question
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\EmbedMany(targetDocument="Answer") */
  private $answers;

  public function setId($value){
      $this->id = $value;
  }

  public function __construct()
  {
    $this->answers = new ArrayCollection();
  }

  public function setContent($value){
    $this->content = $value;
  }

  public function addAnswer($answer){
    $this->answers->add($answer);
  }

  public function getContent() {
    return $this->content;
  }

}