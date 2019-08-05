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
    public function inValidPinByCandidateId($examId, $candidateId) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('examId')->equals($examId)
                    ->field('candidate.id')->equals($candidateId)
                    
                    ->field('candidate.isPinValid')->set(false)
                    ->getQuery()
                    ->execute();
        
        return $result;

    }

    public function getExamResult($examId, $candiateId, $questionId) {
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

    public function updateQuestionMark($examId, $candiateId, $questionId, $mark, $comment) {
        $queryBuilder = $this->createQueryBuilder();       
        $document = $queryBuilder
                    ->updateOne()

                    ->field('examId')->equals($examId)
                    ->field('candidate.id')->equals($candiateId)
                    ->field('test.sections.questions.id')->equals($questionId)
                    ->field('test.sections.id')->equals("5d1037e1ce10c90500007d87")
                    ->field('test.sections.questions.$[element].comment')->set($comment)
                    
                    //->field('test.sections.questions.$.comment')->expr()->operator('$arrayFilters','{}')
                    //->field('$test.sections.questions', "question", $builder->expr()->eq('$$question.id', $questionId))
                    //->field('test.sections.questions.$.mark')->set($mark)
                    ->setNewObj(
                        [
                            '$set' => [
                                'test.sections.questions.$[element].comment' => 'this is comment for you'
                            ],
                            'arrayFilters' => [ 
                                '{ "element": { $gte: 100 } }' 
                            ]
                        ]
                            
                    )

                    ->getQuery()
                    ->execute();
                echo '<pre>'.print_r($document, true).'</pre>'; die;
        return $document;

    }

    public function hasQuestionNotScored($id) {
        $queryBuilder = $this->createAggregationBuilder();       
        $count = $queryBuilder
                    ->match()
                        ->field('id')->equals($id)
                        ->field('test.sections.questions.questionInfo.isScored')->equals(false)
                    
                    ->execute()->count();
        return !!$count;
    }

    public function getExamNotStartedByTestId($testId) {
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

    public function getExamJoined($type, $objectId) {
        $examResult = $this->createQueryBuilder()
                            ->field('candidate.objectId')->equals($objectId)
                            ->field('candidate.type')->equals($type)
                            ->select(['time','title', 'startDate', 'examId', 'candidate', 'resultSummary'])
                            ->sort('startDate', 'desc')
                            ->getQuery()
                            ->execute();
        
        return $examResult;
    }
}
