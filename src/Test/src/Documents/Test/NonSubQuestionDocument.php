<?php
namespace Test\Documents\Test;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @ODM\EmbeddedDocument
 */

class NonSubQuestionDocument extends QuestionDocument
{
    /** @ODM\EmbedMany(targetDocument="\Test\Documents\Question\AnswerDocument") */
    private $answers;

    public function __construct()
    {
      parent::__construct();
      $this->answers = new ArrayCollection();
    }

    public function addAnswer($answer){
      $this->answers->add($answer);
    }

    /**
     * Get the value of answers
     */ 
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set the value of answers
     *
     * @return  self
     */ 
    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }
}