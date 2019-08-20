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

class WritingQuestionRepository extends QuestionRepository
{
    public function generateRandomQuestion($typeId, $numberSubQuestion, $sources, $notInQuestions, $toClass) {
        $aggregationBuilder = $this->createAggregationBuilder();
        $question = $aggregationBuilder
                        ->hydrate($toClass)
                        ->match()
                            ->addOr($aggregationBuilder->matchExpr()->field('typeId')->equals($typeId))
                            ->addOr($aggregationBuilder->matchExpr()->field('parentTypeId')->equals($typeId))
                            ->field('id')->notIn($notInQuestions) 
                            ->field('source')->notIn($sources)                         
                        ->sample(1)
                        ->execute()
                        ->getSingleResult();
        
        return $question;
    }

    public function getExamInfo($pin) {
       
        $now  = new \DateTime(date('Y-m-d H:i:s',\time()));
        $builder = $this->createAggregationBuilder();
        $command = $builder
                ->hydrate(\Test\Documents\Exam\ExamDocument::class)
                ->match()
                    ->field('candidates.pin')->equals($pin)
                    ->field('startDate')->gte($now)                
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

        //$timeBefore5MinutesAgo  = new \DateTime(date('Y-m-d H:i:s',\time() - 36000 * 60));
        //$mongoDateBefore5MinutesAgo = new \MongoDate($timeBefore5MinutesAgo->getTimestamp());

        $now  = new \DateTime(date('Y-m-d H:i:s',\time()));
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
}
