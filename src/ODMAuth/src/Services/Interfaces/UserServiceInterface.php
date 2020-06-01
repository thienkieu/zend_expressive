<?php

declare(strict_types=1);

namespace ODMAuth\Services\Interfaces;

Interface UserServiceInterface
{
    public function getUserById($userId);
}
