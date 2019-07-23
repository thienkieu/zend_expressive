<?php

declare(strict_types=1);

namespace Test\Services\Interfaces;


interface TypeServiceInterface
{
    public function getTypes($content, & $messages, $pageNumber = 1, $itemPerPage = 25);

    public function createType($dto, & $returnDTO, & $messages);

    public function getAllTypes(& $ret);

    public function getTypeByName($parentName, $subTypeName = '');
    public function getTypeById($id);
}
