<?php

declare(strict_types=1);

namespace Test\Services\Interfaces;

interface AuthorizationService
{
    public function isAllow($objectId, $objectType, $codeAction);
}