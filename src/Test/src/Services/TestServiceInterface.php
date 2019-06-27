<?php

declare(strict_types=1);

namespace Test\Services;


interface TestServiceInterface
{
    public function createTest(\Test\DTOs\Test\BaseTestDTO $testDTO, & $messages);
    public function getTests(& $tests, & $messages, $pageNumber = 1, $itemPerPage = 25);
}
