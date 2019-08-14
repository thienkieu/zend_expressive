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
    public function generateRandomQuestion($typeId, $numberSubQuestion, $sources, $notInQuestions, $toClass) {
        $aggregationBuilder = $this->createAggregationBuilder();
        $question = $aggregationBuilder
                        ->hydrate($toClass)
                        ->match()
                            ->field('type')->equals($typeId)
                            ->field('source')->notIn($sources)
                            ->field('id')->notIn($notInQuestions)
                            ->field('numberSubQuestion')->gte($numberSubQuestion)
                        ->sample(1)
                        ->execute()
                        ->getSingleResult();
        
        return $question;
    }


    public function getQuestionWithPagination($filterData, $itemPerPage, $pageNumber) {
        $filterQuery = $this->getFilterQuery($filterData);
        $totalDocument = $filterQuery->getQuery()->execute()->count();        
        $filterQuery = $filterQuery->field('type')->prime(true)
                            ->field('source')->prime(true);

        if (!empty($itemPerPage)) {
            $filterQuery = $filterQuery->limit($itemPerPage)
                                    ->skip($itemPerPage*($pageNumber-1));
        }
        
        $data = $filterQuery->sort('createDate', 'desc')
                            ->getQuery()
                            ->execute();
       
        return [
            'totalDocument' => $totalDocument,
            'questions' => $data 
        ];
    }

    public function getFilterQuery($filterData) {
        $builder = $this->createQueryBuilder();
        $type = $filterData->type;
        $builder = $builder
                        ->addOr($builder->expr()->field('content')->equals(new \MongoRegex('/.*'.$filterData->content.'.*/i')))
                        ->addOr($builder->expr()->field('subQuestions.content')->equals(new \MongoRegex('/.*'.$filterData->content.'.*/i')))
                        ->addOr($builder->expr()->field('subQuestions.answers.content')->equals(new \MongoRegex('/.*'.$filterData->content.'.*/i')));
    
        if ($type) {
            $builder->field('typeId')->equals($type);
        }

        $parentType = '';
        if (isset($filterData->parentType)) {
            $parentType = $filterData->parentType;
        }

        if ($parentType) {
            $builder->field('parentTypeId')->equals($parentType);
        }

        return $builder;
    }

}
