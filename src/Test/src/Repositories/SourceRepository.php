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

class SourceRepository extends DocumentRepository
{
    public function getSourceWithPagination($content, $itemPerPage, $pageNumber) {
        $filterQuery = $this->getFilterQuery($content);
        $totalDocument = $filterQuery->getQuery()->execute()->count();        
        $data = $filterQuery->limit($itemPerPage)
                            ->skip($itemPerPage*($pageNumber-1))
                            ->sort('name', 'desc')
                            ->getQuery()
                            ->execute();
       
        return [
            'totalDocument' => $totalDocument,
            'sources' => $data 
        ];
    }

    public function getFilterQuery($content) {
        $builder = $this->createQueryBuilder();
        $builder = $builder->field('name')->equals(new \MongoRegex('/.*'.$content.'.*/i'));
           
        return $builder;
    }

}
