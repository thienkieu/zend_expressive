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
use  Doctrine\ODM\MongoDB\Query\Expr;
use date;

class QuestionRepository extends DocumentRepository
{
    public function generateRandomQuestion($typeId, $numberSubQuestion, $sources, $notInQuestions, $toClass, $platform, $user) {
        $aggregationBuilder = $this->createAggregationBuilder();
        $question = $aggregationBuilder
                        ->hydrate($toClass)
                        ->match()
                            ->addOr($aggregationBuilder->matchExpr()->field('typeId')->equals($typeId))
                            ->addOr($aggregationBuilder->matchExpr()->field('parentTypeId')->equals($typeId))
                            ->field('source')->notIn($sources)
                            ->field('id')->notIn($notInQuestions)
                            ->field('numberSubQuestion')->gte($numberSubQuestion)
                            ->field('platform')->equals($platform)
                        ->sample(1)
                        ->execute()
                        ->current();
        
        return $question;
    }

    public function getQuestionWithPagination($filterData, $itemPerPage, $pageNumber) {
        $totolDocumentQuery = $filterQuery = $this->getFilterQuery($filterData);
        $totalDocument = $totolDocumentQuery->count()->getQuery()->execute();

        $filterQuery = $this->getFilterQuery($filterData);
        $filterQuery = $filterQuery->field('type')->prime(true)
                            ->field('source')->prime(true);
        
        
        if (!empty($itemPerPage)) {
            $filterQuery = $filterQuery->limit((int)$itemPerPage)
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
        $content = '';
        if (isset($filterData->content)) {
            $content = $filterData->content;
        }
        
        if ($content) {
            $builder = $builder
                            ->addOr($builder->expr()->field('content')->equals(new \MongoDB\BSON\Regex($filterData->content, 'i')))
                            ->addOr($builder->expr()->field('subQuestions.content')->equals(new \MongoDB\BSON\Regex($filterData->content,'i')))
                            ->addOr($builder->expr()->field('subQuestions.answers.content')->equals(new \MongoDB\BSON\Regex($filterData->content, 'i')));
        }
        
        $type = '';
        if (isset($filterData->type)) {
            $type = $filterData->type;
        }
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

        $source = '';
        if (isset($filterData->source)) {
            $source = $filterData->source;
        }
        if ($source) {
            $builder->field('source')->equals($source);
        }

        $platform = "";
        if (isset($filterData->platform)) {
            $platform = $filterData->platform;
        }
        if ($platform) {
            $builder->field('platform')->equals($platform);
        }

        return $builder;
    }

}
