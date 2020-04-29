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

class TestWithSectionRepository extends DocumentRepository
{
    public function getTestWithPagination($filterData, $itemPerPage, $pageNumber) {
        $totalDocumentQuery = $this->getFilterQuery($filterData);
        $totalDocument = $totalDocumentQuery->count()->getQuery()->execute(); 
        
        $filterQuery = $this->getFilterQuery($filterData);
        $data = $filterQuery->limit((int)$itemPerPage)
                                    ->skip($itemPerPage*($pageNumber-1))
                                    ->sort('createDate', 'desc')
                                    ->getQuery()
                                    ->execute();
        return [
            'totalDocument' => $totalDocument,
            'tests' => $data 
        ];
    }

    protected function getFilterQuery($filterData) {
        $queryBuilder = $this->createQueryBuilder();
        if (!empty($filterData->title)) {
            $queryBuilder
                ->addOr($queryBuilder->expr()->field('title')->equals(new \MongoDB\BSON\Regex($filterData->title, 'i')))
                ->addOr($queryBuilder->expr()->field('sections.description')->equals(new \MongoDB\BSON\Regex($filterData->title, 'i')));
            
        }
        
        return $queryBuilder;
    }

}
