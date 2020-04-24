<?php

declare(strict_types=1);

namespace Test\Services;

interface TrackingConnectServiceInterface
{
    public function getLatestDisconnect($token);
}
