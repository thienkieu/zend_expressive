<?php

declare(strict_types=1);

return [
    'requestToDTO' => [
        'section.create' => \Test\DTOs\SectionDTO::class,
        'test.create'   => \Test\DTOs\Test\BaseTestDTO::class,
        'question.source.create'   => \Test\DTOs\Question\SourceDTO::class,
        'exam.create' => \Test\DTOs\Exam\ExamDTO::class,
        'exam.updateAnswer' => \Test\DTOs\ExamResult\UserAnswerDTO::class,
    ],
    'validator' => [
        'test.create' => [  
            \Test\Validator\CreateTestWithSectionValidatorAdapter::class,         
        ]
    ]
];