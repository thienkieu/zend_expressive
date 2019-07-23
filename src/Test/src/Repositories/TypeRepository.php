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

class TypeRepository extends DocumentRepository
{
    public function getType($typeName) {
       
        $builder = $this->createQueryBuilder();
        $document = $builder
                    ->field('name')->equals($typeName)
                    ->field('parentType')->prime(true)
                    ->getQuery()
                    ->getSingleResult();

        return $document;
    }

    public function getTypeByName($parentName, $subTypeName) {
        $parentType = $this->findOneBy(['name' => $parentName]);
        if (empty($subTypeName)) return $parentType;

        if (!$parentType) {
            return null;
        }

        return $this->findOneBy(['name' => $subTypeName, 'parentType'=>$parentType->getId()]);
    }
}
