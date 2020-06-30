<?php

declare(strict_types=1);

namespace Test\Services;


interface ExamServiceInterface
{
    public function createOrUpdateExam(\Test\DTOs\Exam\ExamDTO $testDTO,  & $dto, & $messages);
    public function updateTestOfExam(\Test\DTOs\Exam\EditTestOfExamDTO $editTestOfExamDTO,  & $outDTO, & $messages);
    public function generateExamTest($test, & $messages, $keepCorrectAnswer = false, $options = []);
    public function createExamSample(\Test\DTOs\Test\BaseTestDTO $testDTO, & $dto, & $messages);
    public function getExams($filterCriterial, & $ret, & $messages, $pageNumber = 1, $itemPerPage = 25);
    public function getExam($id, & $ret, &$messages);
    public function updateExamResultSummary($examId, $candidateId, $resultSummary);
    public function enterPin($dto, & $results, & $messages);
    public function deleteExam($id, & $messages);
    public function completeExam($id, & $messages);
    public function existExamWithTitle($title, &$document);
    public function getTypes();
}
