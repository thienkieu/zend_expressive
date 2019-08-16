<?php

declare(strict_types=1);

namespace ODMAuth\Services\Interfaces;

use Zend\Log\Logger;

Interface AuthorizationServiceInterface
{
    public function isAllow($userId, $action, &$messages);
    
}