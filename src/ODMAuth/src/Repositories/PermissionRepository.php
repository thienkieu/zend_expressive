<?php
declare(strict_types=1);

namespace ODMAuth\Repositories;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class PermissionRepository extends DocumentRepository
{
    public function getPermissions($filterData, $itemPerPage, $pageNumber) {
        $totalDocumentQuery = $this->getFilterQuery($filterData);
        $totalDocument = $totalDocumentQuery->count()->getQuery()->execute();        
        
        $filterQuery = $this->getFilterQuery($filterData);
        if (!empty($itemPerPage)) {
            $filterQuery = $filterQuery->limit((int)$itemPerPage)
                                    ->skip($itemPerPage*($pageNumber-1));
        }
        
        $data = $filterQuery->sort('createDate', 'desc')
                            ->getQuery()
                            ->execute();
        return [
            'totalDocument' => $totalDocument,
            'permissions' => $data 
        ];
    }

    public function getFilterQuery($filterData) {
        $builder = $this->createQueryBuilder();
        
        return $builder;
    }

    public function getListByIds($ids) {
        return $this->createQueryBuilder()->field('id')->in($ids)->getQuery()->toArray();
    }
}
