<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace Test\Repositories;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\Tools\Pagination\Paginator;

class ExamResultHasSectionTestRepository extends DocumentRepository
{
    public function updateAnwser($examId, $candiateId, $questionId, $subQuestionId, $answerId) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('examId')->equals($examId)
                    ->field('candidate.id')->equals($candiateId)  
                    ->field('test.sections.questions.id')->equals($questionId)  
                    ->field('test.sections.questions.questionInfo.id')->equals('5d075b32ce10c915bc006d9c')                    
     
                    ->field('test.sections.questions.questionInfo.isUserChoose')->set(true)
                    ->getQuery()
                    ->execute();
        
        return $result;
       
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('examId')->equals($examId)
                    ->field('candidate.id')->equals($candiateId)
                    ->field('test.sections.questions.id')->equals($questionId)                   
                    ->field('test.sections.questions.questionInfo.subQuestions.answers.id')->equals($answerId)
                    
                    ->field('test.sections.questions.questionInfo.subQuestions.answers.$.isUserChoose')->set(true)
                    ->getQuery()
                    ->execute();
        
        return $result;

    }

}
