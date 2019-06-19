<?php

declare(strict_types=1);

namespace Test\Services;


interface DoExamResultServiceInterface
{
    public function updateAnswer($dto, & $messages);
    public function updateRepeatTimes($dto, & $messages);
    public function updateWritingAnswer($dto, & $messages);
}
