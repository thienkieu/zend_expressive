<?php

declare(strict_types=1);

namespace Test\Services;


interface DoExamResultServiceInterface
{
    public function updateAnswer($dto, & $messages);
    public function synchronyTime($dto, & $outRemainTime, & $messages);
}
