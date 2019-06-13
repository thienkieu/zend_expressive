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

class ExamWithSectionRepository extends DocumentRepository
{
    public function getExamInfo($pin) {
       
        $now  = new \DateTime(date('Y-m-d H:i:s',\time()));
        $builder = $this->createAggregationBuilder();
        $command = $builder
                ->hydrate(\Test\Documents\Exam\ExamHasSectionTestDocument::class)
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
        
        /*echo '<pre>'.print_r($data, true).'</pre>'; die;
        $exam = null;
        if ($data) {
            $exam = new \Test\Documents\Exam\ExamHasSectionTestDocument();
            $exam->setTitle($data['title']);
            $exam->setId($data['_id']->__toString());
            $exam->setStartDate($data['startDate']);

            $candidates = $data['candidates'];
            if (count($candidates) > 0) {
            
                foreach($candidates as $candidate) {
                    $candidateDocument = new \Test\Documents\Exam\CandidateDocument();
                    $candidateDocument->setId($candidate['_id']->__toString()) ;
                    $candidateDocument->setObjectId($candidate['objectId']);
                    $candidateDocument->setType($candidate['type']);
                    $candidateDocument->setName($candidate['name']);
                    $candidateDocument->setEmail($candidate['email']);
                    $candidateDocument->setPin($candidate['pin']);

                    $exam->addCandidate($candidateDocument);
                }
            }

        }*/
        
        /*
        $queryBuilder = $this->createQueryBuilder();
        $expr = new Expr();
        $equalPin = $expr->field('pin')->equals($pin);
        //echo '<pre>'.print_r($equalPin, true).'</pre>'; die;

        $exam = $queryBuilder
                ->select(['candidates.pin'])
                ->field('id')->equals($id)
                ->field('candidates.pin')->equals($pin)
                ->field('candidates')->elemMatch($equalPin)                
                ->getQuery()
                ->getSingleResult();

        if ($exam) {
            $candidates = $exam->getCandidates();
            foreach ($candidates as $key => $value) {
                echo $value->getId().'<br/>';
                echo $value->getEmail().'<br/>';
                echo $value->getPin().'<br/>';
            }
            die;
        }
        echo '<pre>'.print_r($exam, true).'</pre>'; die;*/

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
                ->hydrate(\Test\Documents\Exam\ExamHasSectionTestDocument::class)
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
        
        /*echo '<pre>'.print_r($data, true).'</pre>'; die;
        $exam = null;
        if ($data) {
            $exam = new \Test\Documents\Exam\ExamHasSectionTestDocument();
            $exam->setTitle($data['title']);
            $exam->setId($data['_id']->__toString());
            $exam->setStartDate($data['startDate']);

            $candidates = $data['candidates'];
            if (count($candidates) > 0) {
            
                foreach($candidates as $candidate) {
                    $candidateDocument = new \Test\Documents\Exam\CandidateDocument();
                    $candidateDocument->setId($candidate['_id']->__toString()) ;
                    $candidateDocument->setObjectId($candidate['objectId']);
                    $candidateDocument->setType($candidate['type']);
                    $candidateDocument->setName($candidate['name']);
                    $candidateDocument->setEmail($candidate['email']);
                    $candidateDocument->setPin($candidate['pin']);

                    $exam->addCandidate($candidateDocument);
                }
            }

        }*/
        
        /*
        $queryBuilder = $this->createQueryBuilder();
        $expr = new Expr();
        $equalPin = $expr->field('pin')->equals($pin);
        //echo '<pre>'.print_r($equalPin, true).'</pre>'; die;

        $exam = $queryBuilder
                ->select(['candidates.pin'])
                ->field('id')->equals($id)
                ->field('candidates.pin')->equals($pin)
                ->field('candidates')->elemMatch($equalPin)                
                ->getQuery()
                ->getSingleResult();

        if ($exam) {
            $candidates = $exam->getCandidates();
            foreach ($candidates as $key => $value) {
                echo $value->getId().'<br/>';
                echo $value->getEmail().'<br/>';
                echo $value->getPin().'<br/>';
            }
            die;
        }
        echo '<pre>'.print_r($exam, true).'</pre>'; die;*/

        return $document;
    }
}
