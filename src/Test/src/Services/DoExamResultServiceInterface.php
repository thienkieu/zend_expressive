<?php

declare(strict_types=1);

namespace Test\Services;

interface DoExamResultServiceInterface
{
    public function updateAnswer($dto, & $messages);
    public function updateQuestionMark($dto, & $messages);
    public function updateSectionMark($dto, & $messages);
    public function synchronyTime($dto, & $outRemainTime, & $messages);
    public function finish($dto, & $messages);
    public function getExamResult($dto, & $messages, & $examResultDTO);
    public function isExistResultOfExam($examId);
    public function addManualResult($examResultDTO, &$messages);
    public function getExamJoined(& $exams, $dto);
    public function getLatestExamJoined(& $exams, $dto);
    public function inValidPin($dto, & $messages);
    public function isExistExamResultMarkDone($examId);
}
