<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\EmbeddedDocument
 */

class WritingQuestionDocument extends QuestionDocument
{  
    /** @ODM\Field(type="string") */
  private $answer;

  /**
   * Get the value of answer
   */ 
  public function getAnswer()
  {
    return $this->answer;
  }

  /**
   * Set the value of answer
   *
   * @return  self
   */ 
  public function setAnswer($answer)
  {
    $this->answer = $answer;

    return $this;
  }
}