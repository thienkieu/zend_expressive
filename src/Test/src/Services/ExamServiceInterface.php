<?php

declare(strict_types=1);

namespace Test\Services;


interface ExamServiceInterface
{
    public function createExam(\Test\DTOs\Exam\ExamDTO $testDTO,  & $dto, & $messages);
    public function getTests(& $tests, & $messages, $pageNumber = 1, $itemPerPage = 25);
    public function enterPin($dto, & $results, & $messages);
}
