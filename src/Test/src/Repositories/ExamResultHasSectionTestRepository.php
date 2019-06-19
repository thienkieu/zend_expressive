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
    public function updateAnwser($examId, $candiateId, $questionId, $questionInfoId, $subQuestionId, $answerId) {
        $queryBuilder = $this->createQueryBuilder();       
        $document = $queryBuilder
                    ->field('examId')->equals($examId)
                    ->field('candidate.id')->equals($candiateId)
                    //->field('test.sections.questions.id')->equals($questionId)
                    //->field('test.sections.questions.questionInfo.id')->equals($questionInfoId)
                    //->field('test.sections.questions.questionInfo.subQuestions.id')->equals($subQuestionId)
                    //->field('test.sections.questions.questionInfo.subQuestions.id')->equals('5d09aec2ce10c911f4005cb7')
                    //->field('test.sections.questions.questionInfo.subQuestions.anwsers.id')->equals($answerId)

                    ->getQuery()
                    ->getSingleResult();
            
        return $document;

    }

}
