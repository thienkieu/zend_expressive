<?php
namespace App\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\Document(collection="sections")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"reading"="ReadingSection", "listening"="ListeningSection", "writing"="WritingSection"})
 */

class ReadingSection
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

  /** @ODM\EmbedMany(targetDocument="Question") */
  private $questions;

  public function __construct()
  {
    $this->questions = new ArrayCollection();
  }

  public function setId($value){
      $this->id = $value;
  }

  public function setContent($value){
    $this->content = $value;
  }

  public function addQuestion($question){
    $this->questions->add($question);
  }

  public function getContent() {
    return $this->content;
  }

  public function getQuestions() {
    return $this->questions;
  }

}