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
use  Doctrine\MongoDB\Query\Expr;
use date;

class ExamRepository extends DocumentRepository
{
    private $canAccessExamBefore = (1 * 24 * 60 * 60);
    
    public function refreshPin($examId, $candidateId, $newPin) {
        $queryBuilder = $this->createQueryBuilder();
        $result = $queryBuilder
                    ->updateOne()
                    
                    ->field('id')->equals($examId)
                    ->field('candidates.id')->equals($candidateId)
                    
                    ->field('candidates.$.pin')->set($newPin)
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
       
        $now  = new \DateTime(date('Y-m-d H:i:s',\time() - $this->canAccessExamBefore));
        $builder = $this->createAggregationBuilder();
        $command = $builder
                ->hydrate(\Test\Documents\Exam\ExamDocument::class)
                ->match()
                    ->field('candidates.pin')->equals($pin)
                    ->field('startDate')->gte($now)                
                ->project()   
                    ->includeFields(['title', 'startDate', 'test', 'time'])                
                    ->excludeFields(['candidates'])
                    ->filter('$candidates', "candidate", $builder->expr()->eq('$$candidate.pin', $pin))
                    
                ->execute();
        //echo '<pre>'.print_r($command, true).'</pre>'; die;

        $candidateDocument = null;
        $document = $command->getSingleResult();
        
       
        return $document;
    }

    public function getCandidateInfo($pin) {
        
        $expr = new Expr();
        $equalPin = $expr->field('candidate.pin')->equals($pin);

        //$timeBefore5MinutesAgo  = new \DateTime(date('Y-m-d H:i:s',\time() - 36000 * 60));
        //$mongoDateBefore5MinutesAgo = new \MongoDate($timeBefore5MinutesAgo->getTimestamp());

        $now  = new \DateTime(date('Y-m-d H:i:s',\time() - $this->canAccessExamBefore));
        $builder = $this->createAggregationBuilder();
        $command = $builder
                ->hydrate(\Test\Documents\Exam\ExamDocument::class)
                ->match()
                    ->field('candidates.pin')->equals($pin)
                    ->field('startDate')->gte($now)                
                ->project()   
                    ->includeFields(['title', 'startDate'])                
                    ->field('candidates')
                    ->filter('$candidates', "candidate", $builder->expr()->eq('$$candidate.pin', $pin))
                    
                ->execute();
        //echo '<pre>'.print_r($command, true).'</pre>'; die;

        $candidateDocument = null;
        $document = $command->getSingleResult();
        
        return $document;
    }

    public function getExamWithPagination($filterCriterial, $itemPerPage, $pageNumber) {
        $filterQuery = $this->getFilterQuery($filterCriterial);
        $totalDocument = $filterQuery->getQuery()->execute()->count();        
        $data = $filterQuery->limit($itemPerPage)
                                    ->skip($itemPerPage*($pageNumber-1))
                                    ->getQuery()
                                    ->execute();
        return [
            'totalDocument' => $totalDocument,
            'exams' => $data 
        ];
    }

    protected function getFilterQuery($filterData) {
        $queryBuilder = $this->createQueryBuilder();
       

        $fromDate = $filterData->getFromDate();
        $toDate = $filterData->getToDate();

        if (!empty($fromDate) && !empty($toDate)) {
            $queryBuilder
                ->field('startDate')->gte($fromDate)
                ->field('startDate')->lte($toDate);
        }


        return $queryBuilder;
    }
}
