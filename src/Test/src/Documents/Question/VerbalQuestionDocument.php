<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(repositoryClass=Test\Repositories\VerbalQuestionRepository::class)
 */

class VerbalQuestionDocument extends QuestionDocument
{  
    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->mark = \Config\AppConstant::DefaultVerbalMark;
    }
}