<?php

declare(strict_types=1);

namespace Test\Services;

interface DoExamResultServiceInterface
{
    public function updateAnswer($dto, & $messages);
    public function updateQuestionMark($dto, & $messages);
    public function synchronyTime($dto, & $outRemainTime, & $messages);
    public function finish($dto, & $messages);
    public function getExamResult($dto, & $messages, & $examResultDTO);
}