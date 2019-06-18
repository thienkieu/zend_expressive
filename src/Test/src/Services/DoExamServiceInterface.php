<?php

declare(strict_types=1);

namespace Test\Services;


interface DoExamServiceInterface
{
    public function doExam($dto, & $results, & $messages);
    public function updateAnswer($dto, & $messages);
}
