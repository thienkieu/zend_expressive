<?php
namespace Test\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(collection="sections")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"reading"="ReadingSection", "listening"="ListeningSection", "writing"="WritingSection"})
 */

class WritingSection
{
  /** @ODM\Id */
  protected $id;

  /** @ODM\Field(type="string") */
  private $content;

  public function setId($value){
      $this->id = $value;
  }

  public function setContent($value){
    $this->content = $value;
  }
}