<?php

declare(strict_types=1);

namespace Test\Services\Question;


interface QuestionServiceInterface
{
    public function generateQuestion($citerial, $notInSource);

    public function getQuestions($dto, $pageNumber, $itemPerPage);

    public function caculateMark(&$document);
}
