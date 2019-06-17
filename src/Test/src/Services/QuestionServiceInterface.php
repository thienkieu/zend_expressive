<?php

declare(strict_types=1);

namespace Test\Services;


interface QuestionServiceInterface
{
    public function generateQuestion($citerial, $notInSource);
}
