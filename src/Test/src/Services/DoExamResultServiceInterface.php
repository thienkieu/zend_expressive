<?php

declare(strict_types=1);

namespace Test\Services;


interface DoExamResultServiceInterface
{
    public function updateAnswer($dto, & $messages);
}
