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

class TestTemplateRepository extends DocumentRepository
{
    public function getTemplateWithPagination($filterData, $itemPerPage, $pageNumber) {
        $filterQuery = $this->getFilterQuery($filterData);
        $totalDocument = $filterQuery->getQuery()->execute()->count();  
          
        $data = $filterQuery->limit($itemPerPage)
                                    ->skip($itemPerPage*($pageNumber-1))
                                    ->sort('createDate', 'desc')
                                    ->getQuery()
                                    ->execute();
        return [
            'totalDocument' => $totalDocument,
            'templates' => $data 
        ];
    }

    protected function getFilterQuery($filterData) {
        $queryBuilder = $this->createQueryBuilder();
        if (!empty($filterData->title)) {
            $queryBuilder
                ->addOr($queryBuilder->expr()->field('title')->equals(new \MongoRegex('/.*'.$filterData->title.'*/i')))
                ->addOr($queryBuilder->expr()->field('sections.description')->equals(new \MongoRegex('/.*'.$filterData->title.'*/i')));
            
        }
        
        return $queryBuilder;
    }

}
