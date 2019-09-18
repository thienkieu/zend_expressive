<?php

declare(strict_types=1);

namespace Test\Services;


interface DoExamServiceInterface
{
    public function doExam($dto, & $results, & $messages);
    public function updateAnswer($dto, & $messages);
    public function isAllowAccessExam($examDocument);
    public function inValidPin($examId, $candidateId);
}
