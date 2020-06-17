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
use  Doctrine\ODM\MongoDB\Query\Expr;
use date;

class ExamWithSectionRepository extends DocumentRepository
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

    public function getCandidateInfo($pin) {
        
        $expr = new Expr($this->getDocumentManager());
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
}
