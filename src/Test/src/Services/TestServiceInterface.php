<?php

declare(strict_types=1);

namespace Test\Services;


interface TestServiceInterface
{
    public function createTest(\Test\DTOs\Test\BaseTestDTO $testDTO,  & $dto, & $messages);
}
