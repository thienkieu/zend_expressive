<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(repositoryClass="\Test\Repositories\WritingQuestionRepository")
 */

class WritingQuestionDocument extends QuestionDocument
{  
    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->mark = \Config\AppConstant::DefaultWritingMark;
    }
}