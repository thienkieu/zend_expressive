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

class PlatformRepository extends DocumentRepository
{
    public function getPlatforms() {
        $builder = $this->createAggregationBuilder();
        $documents = $builder
                    ->group()
                        ->field('parentPlatform')
                    
                    ->execute();
        echo count($documents);die;
        return $documents;
    }

    public function getPlatform($platformName) {
       
        $builder = $this->createQueryBuilder();
        $document = $builder
                    ->field('name')->equals($platformName)
                    ->field('parentPlatform')->prime(true)
                    ->getQuery()
                    ->getSingleResult();

        return $document;
    }

    public function getPlatformByName($parentName, $subPlatformName) {
        $parentPlatform = $this->findOneBy(['name' => $parentName]);
        if (empty($subPlatformName)) return $parentPlatform;

        if (!$parentPlatform) {
            return null;
        }

        return $this->findOneBy(['name' => $subTypeName, 'parentPlatform'=>$parentPlatform->getId()]);
    }

    public function findParentPlatform() {
        $builder = $this->createQueryBuilder();
        $documents = $builder
                    ->field('parentPlatform')->equals(null)
                    ->getQuery()
                    ->execute();

        return $documents;
    }
    
}
