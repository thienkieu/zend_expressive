<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace Test\Repositories;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\Tools\Pagination\Paginator;
use  Doctrine\MongoDB\Query\Expr;
use date;

class ExamRepository extends DocumentRepository
{    
    public function refreshPin($examId, $candidateId, $newPin) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('id')->equals($examId)
                    ->field('candidates.id')->equals($candidateId)
                    
                    ->field('candidates.$.pin')->set($newPin)
                    ->field('candidates.$.isPinValid')->set(true)
                    ->getQuery()
                    ->execute();
        
        return $result;

    }

    public function inValidPinByCandidateId($examId, $candidateId) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('id')->equals($examId)
                    ->field('candidates.id')->equals($candidateId)
                    
                    ->field('candidates.$.isPinValid')->set(false)
                    ->getQuery()
                    ->execute();
        
        return $result;

    }

    public function inValidPin($examId, $pin) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('id')->equals($examId)
                    ->field('candidates.pin')->equals($pin)
                    
                    ->field('candidates.$.isPinValid')->set(false)
                    ->getQuery()
                    ->execute();
        
        return $result;

    }

    public function getExamInfo($pin) {
        $builder = $this->createAggregationBuilder();
        $command = $builder
                ->hydrate(\Test\Documents\Exam\ExamDocument::class)
                ->match()
                    ->field('candidates.pin')->equals($pin)       
                ->project()   
                    ->includeFields(['title', 'startDate', 'test', 'time','type'])                
                    ->excludeFields(['candidates'])
                    ->filter('$candidates', "candidate", $builder->expr()->eq('$$candidate.pin', $pin))
                    
                ->execute();
        //echo '<pre>'.print_r($command, true).'</pre>'; die;

        $candidateDocument = null;
        $document = $command->current();
        
       
        return $document;
    }

    public function getCandidateInfo($pin) {
        
        $expr = new Expr();
        $equalPin = $expr->field('candidate.pin')->equals($pin);

        $builder = $this->createAggregationBuilder();
        $command = $builder
                ->hydrate(\Test\Documents\Exam\ExamDocument::class)
                ->match()
                    ->field('candidates.pin')->equals($pin)       
                ->project()   
                    ->includeFields(['title', 'startDate'])                
                    ->field('candidates')
                    ->filter('$candidates', "candidate", $builder->expr()->eq('$$candidate.pin', $pin))
                    
                ->execute();
        //echo '<pre>'.print_r($command, true).'</pre>'; die;

        $candidateDocument = null;
        $document = $command->current();
        
        return $document;
    }

    public function getExamWithPagination($filterCriterial, $itemPerPage, $pageNumber) {
        $totalDocumentQuery = $this->getFilterQuery($filterCriterial);
        $totalDocument = $totalDocumentQuery->count()->getQuery()->execute(); 

        $filterQuery = $this->getFilterQuery($filterCriterial);
        $queryArray = $filterQuery->getQueryArray();
        //echo '<pre>'.print_r($queryArray, true).'</pre>'; die;
        $data = $filterQuery->limit((int)$itemPerPage)
                                    ->skip($itemPerPage*($pageNumber-1))
                                    ->sort('createDate', 'desc')
                                    ->getQuery()
                                    ->execute();
        return [
            'totalDocument' => $totalDocument,
            'exams' => $data 
        ];
    }

    protected function getFilterQuery($filterData) {
        $queryBuilder = $this->createQueryBuilder();
        if (!empty($filterData->getId())) {
            $queryBuilder
                ->addAnd($queryBuilder->expr()->field('id')->equals($filterData->getId()));
        }

        if (!empty($filterData->getTitle())) {
            $queryBuilder->addAnd(
                $queryBuilder->expr()
                ->addOr($queryBuilder->expr()->field('test.title') ->equals(new \MongoDB\BSON\Regex($filterData->getTitle(), 'i')))
                ->addOr($queryBuilder->expr()->field('title')->equals(new \MongoDB\BSON\Regex($filterData->getTitle(), 'i')))

                ->addOr($queryBuilder->expr()->field('test.title')->equals(new \MongoDB\BSON\Regex($filterData->getTitle(), 'i')))
                ->addOr($queryBuilder->expr()->field('title')->equals(new \MongoDB\BSON\Regex($filterData->getTitle(), 'i')))
            );

        }

        if (!empty($filterData->getCandidateIdOrNameOrEmail())) {
            $queryBuilder->addAnd(
                $queryBuilder->expr()
                ->addOr($queryBuilder->expr()->field('candidates.objectId')->equals($filterData->getCandidateIdOrNameOrEmail()))
                ->addOr($queryBuilder->expr()->field('candidates.email')->equals(new \MongoDB\BSON\Regex($filterData->getCandidateIdOrNameOrEmail(), 'i')))
                ->addOr($queryBuilder->expr()->field('candidates.name')->equals(new \MongoDB\BSON\Regex($filterData->getCandidateIdOrNameOrEmail(), 'i')))
            );
        }

        $fromDate = $filterData->getFromDate();
        $toDate = $filterData->getToDate();
        if (!empty($fromDate) && !empty($toDate)) {
            $queryBuilder->addAnd(
                $queryBuilder->expr()
                    ->field('startDate')->gte($fromDate)
                    ->field('startDate')->lte($toDate)
            );
                
        }

        if (!empty($fromDate) && empty($toDate)) {
            $queryBuilder->addAnd(
                $queryBuilder->expr()
                    ->field('startDate')->gte($fromDate)
            );
                
        }

        if (empty($fromDate) && !empty($toDate)) {
            $queryBuilder->addAnd(
                $queryBuilder->expr()
                    ->field('startDate')->lte($toDate)
            );
                
        }

        return $queryBuilder;
    }

    public function getExamNotStartedByTestId($testId) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->field('test.referId')->equals($testId)
                    ->field('isStarted')->notEqual(true)
                    ->getQuery()
                    ->execute();
                    
        return $result;

    }

    public function getExamNotStarted() {
        $queryBuilder = $this->createQueryBuilder();
        $results = $queryBuilder
                    ->field('isStarted')->notEqual(true)
                    ->getQuery()
                    ->execute();
                    
        return $results;

    }

    
}
