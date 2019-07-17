<?php

declare(strict_types=1);

return [
    'requestToDTO' => [
        'section.create' => \Test\DTOs\SectionDTO::class,
        'test.create'   => \Test\DTOs\Test\BaseTestDTO::class,
        'test.update'   => \Test\DTOs\Test\BaseTestDTO::class,
        'question.source.create'   => \Test\DTOs\Question\SourceDTO::class,
        'exam.create' => \Test\DTOs\Exam\ExamDTO::class,
        'exam.update' => \Test\DTOs\Exam\ExamDTO::class,
        'exam.updateTest' => \Test\DTOs\Exam\EditTestOfExamDTO::class,
        'exam.exams'    =>   \Test\DTOs\Exam\FilterExamDTO::class,
        'test.viewSampleExam' => \Test\DTOs\Test\BaseTestDTO::class,
        'exam.updateAnswer' => \Test\DTOs\ExamResult\UserAnswerDTO::class,
        'exam.updateQuestionMark' => \Test\DTOs\ExamResult\UpdateQuestionMarkDTO::class,
        'question.type.create'  => \Test\DTOs\Question\TypeDTO::class,
    ]
];