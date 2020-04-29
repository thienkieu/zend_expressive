<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace Test\Repositories;

use MongoDB\BSON\Regex;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\Tools\Pagination\Paginator;
use  Doctrine\MongoDB\Query\Expr;
use date;

class SourceRepository extends DocumentRepository
{
    public function getSourceWithPagination($content, $itemPerPage, $pageNumber) {
        $totalDocumentQuery = $this->getFilterQuery($content);
        $totalDocument = $totalDocumentQuery->count()->getQuery()->execute(); 

        $filterQuery = $this->getFilterQuery($content);
        $data = $filterQuery->limit((int)$itemPerPage)
                            ->skip($itemPerPage*($pageNumber-1))
                            ->sort('createDate', 'desc')
                            ->getQuery()
                            ->execute();
       
        return [
            'totalDocument' => $totalDocument,
            'sources' => $data 
        ];
    }

    public function getFilterQuery($content) {
        $builder = $this->createQueryBuilder();
        $builder = $builder->field('name')->equals(new \MongoDB\BSON\Regex($content, 'i'));
        
        return $builder;
    }

}
