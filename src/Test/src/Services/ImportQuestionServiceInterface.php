<?php

declare(strict_types=1);

namespace Test\Services;


interface ImportQuestionServiceInterface
{
    public function importQuestion($csvFile,  & $dto, & $messages);
}
