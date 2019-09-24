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
use time;

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

    public function removeResultByExamId($examId) {
        $queryBuilder = $this->createQueryBuilder()
        ->remove()
        ->field('examId')->equals($examId)
        ->getQuery()
        ->execute();
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

    public function updateListeningFinished($examId, $candidateId, $sectionId, $questionId) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->findAndUpdate()
                    ->field('examId')->equals($examId)
                    ->field('candidate.id')->equals($candidateId)
                    ->field('test.sections.id')->equals($sectionId)
                    ->field('test.sections.questions.questionInfo.id')->equals($questionId)
                    
                    ->field('test.sections.questions.questionInfo.isFinished')->set(true)
                    ->getQuery()
                    ->execute();
        echo '<pre>'.print_r($result, true).'</pre>'; die;
        return $result;
    }

    public function updateDisconnectTime($examId, $candidateId) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('examId')->equals($examId)
                    ->field('candidates.id')->equals($candidateId)
                    
                    ->field('latestDisconnect')->set(time())
                    ->getQuery()
                    ->execute();
        return $result;
    }

    public function getLatestExamJoined($dto) {
        $queryBuilder = $this->createQueryBuilder();

        if (isset($dto->candidateType) && !empty($dto->candidateType)) {
            $queryBuilder->field('candidate.type')->equals($dto->candidateType);
        }

        if (isset($dto->objectId) && !empty($dto->objectId)) {
            $queryBuilder->field('candidate.objectId')->equals($dto->objectId);
        }

        if (isset($dto->examType) && !empty($dto->examType)) {
            $queryBuilder->field('examType')->equals($dto->examType);
        }

        if (isset($dto->latestDate) && !empty($dto->latestDate)) {
            $date = \DateTime::createFromFormat(\Config\AppConstant::DateTimeFormat, $dto->latestDate);
            $queryBuilder->field('startDate')->gte($date);
        }

        $examResult = $queryBuilder->select(['time','title', 'startDate', 'examId', 'candidate', 'resultSummary'])
        ->sort('startDate', 'desc')
        ->distinct('candidate.objectId')
        ->getQuery()
        ->execute();
        
        return $examResult;
    }

    public function getExamJoined($dto) {
        $queryBuilder = $this->createQueryBuilder();

        if (isset($dto->candidateType) && !empty($dto->candidateType)) {
            $queryBuilder->field('candidate.type')->equals($dto->candidateType);
        }

        if (isset($dto->objectId) && !empty($dto->objectId)) {
            $queryBuilder->field('candidate.objectId')->equals($dto->objectId);
        }

        if (isset($dto->examType) && !empty($dto->examType)) {
            $queryBuilder->field('examType')->equals($dto->examType);
        }

        if (isset($dto->latestDate) && !empty($dto->latestDate)) {
            $date = \DateTime::createFromFormat(\Config\AppConstant::DateTimeFormat, $dto->latestDate);
            $queryBuilder->field('startDate')->gte($date);
        }

        $examResult = $queryBuilder->select(['time','title', 'startDate', 'examId', 'candidate', 'resultSummary'])
        ->sort('startDate', 'desc')
        ->getQuery()
        ->execute();
        
        return $examResult;
    }
}
