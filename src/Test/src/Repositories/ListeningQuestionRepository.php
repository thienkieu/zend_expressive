<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace Test\Repositories;

use Doctrine\ODM\Tools\Pagination\Paginator;
use  Doctrine\MongoDB\Query\Expr;
use date;

class ListeningQuestionRepository extends QuestionRepository
{
    public function getExamInfo($pin) {
        $builder = $this->createAggregationBuilder();
        $command = $builder
                ->hydrate(\Test\Documents\Exam\ExamDocument::class)
                ->match()
                    ->field('candidates.pin')->equals($pin)       
                ->project()   
                    ->includeFields(['title', 'startDate', 'test', 'time'])                
                    //->excludeFields(['candidates'])
                    //->filter('$candidates', "candidate", $builder->expr()->eq('$$candidate.pin', $pin))
                    
                ->execute();
        //echo '<pre>'.print_r($command, true).'</pre>'; die;

        $candidateDocument = null;
        $document = $command->getSingleResult();
        
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
        $document = $command->getSingleResult();
        
        return $document;
    }
}
