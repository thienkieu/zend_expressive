<?php

declare(strict_types=1);

namespace Test\Services;

interface DoExamResultListeningServiceInterface
{
    public function updateDisconnect($dto, & $messages);
}
