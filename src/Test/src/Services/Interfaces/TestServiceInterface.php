<?php

declare(strict_types=1);

namespace Test\Services\Interfaces;

interface TestServiceInterface
{
    public function createTest(\Test\DTOs\Test\BaseTestDTO $testDTO, & $messages, & $resultDTO);
    public function getTests(& $tests, & $messages, $title, $pageNumber = 1, $itemPerPage = 25);
    public function deleteTest($testId, & $messages);

}
