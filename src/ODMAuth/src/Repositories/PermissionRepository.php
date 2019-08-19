<?php
declare(strict_types=1);

namespace ODMAuth\Repositories;
use Doctrine\ODM\MongoDB\DocumentRepository;

class PermissionRepository extends DocumentRepository
{
    public function getPermissions($filterData, $itemPerPage, $pageNumber) {
        $filterQuery = $this->getFilterQuery($filterData);
        $totalDocument = $filterQuery->getQuery()->execute()->count();        
        
        
        if (!empty($itemPerPage)) {
            $filterQuery = $filterQuery->limit($itemPerPage)
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
