<?php
namespace Test\Documents\Question;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(repositoryClass=Test\Repositories\ReadingQuestionRepository::class)
 */

class ReadingQuestionDocument extends HasSubQuestionDocument
{ 
}