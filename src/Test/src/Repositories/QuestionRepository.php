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

class QuestionRepository extends DocumentRepository
{
    public function generateRandomQuestion($type, $subType, $numberSubQuestion, $sources, $toClass) {
        $aggregationBuilder = $this->createAggregationBuilder();
        $question = $aggregationBuilder
                        ->hydrate($toClass)
                        ->match()
                            ->field('type')->equals($type)
                            ->field('subType')->equals($subType)
                            ->field('source')->notIn($sources)
                            ->field('numberSubQuestion')->gte($numberSubQuestion)
                        ->sample(1)
                        ->execute()
                        ->getSingleResult();
        
        return $question;
    }


    public function getQuestionWithPagination($filterData, $itemPerPage, $pageNumber) {
        $filterQuery = $this->getFilterQuery($filterData);
        $totalDocument = $filterQuery->getQuery()->execute()->count();        
        $data = $filterQuery->limit($itemPerPage)
                                    ->skip($itemPerPage*($pageNumber-1))
                                    ->getQuery()
                                    ->execute();
        return [
            'totalDocument' => $totalDocument,
            'questions' => $data 
        ];
    }

   

}
