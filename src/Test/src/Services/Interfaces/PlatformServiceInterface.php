<?php

declare(strict_types=1);

namespace Test\Services\Interfaces;


interface PlatformServiceInterface
{
    public function getPlatforms($content, & $messages, $pageNumber = 1, $itemPerPage = 25);

    public function createPlatform($dto, & $returnDTO, & $messages);

    public function getPlatformByName($parentName, $subPlatformName = '');
    public function getPlatformById($id);
    public function getAllPlatforms(& $ret);
    public function migrationPlatformForQuestion();
}
