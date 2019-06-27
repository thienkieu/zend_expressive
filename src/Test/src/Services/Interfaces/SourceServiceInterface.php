<?php

declare(strict_types=1);

namespace Test\Services\Interfaces;

interface SourceServiceInterface
{
    public function getSources(& $ret, & $message, $pageNumber = 1, $itemPerPage = 25);

    public function createSource($dto, & $returnDTO, & $messages);

    public function getAllSource(& $ret, & $messages);

    public function isExistSourceName($sourceName, & $messages);
}
