<?php

declare(strict_types=1);

namespace Test\Services\Interfaces;

interface TestTemplateServiceInterface
{
    public function createTestTemplate(\Test\DTOs\Test\BaseTestDTO $testDTO, & $messages, & $resultDTO);
    public function getTemplates(& $templates, & $messages, $title, $pageNumber = 1, $itemPerPage = 25);
    public function deleteTestTemplate($testId, & $messages);

}
