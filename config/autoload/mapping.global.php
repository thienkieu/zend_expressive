<?php

declare(strict_types=1);

return [
    'requestToDTO' => [
        'section.create' => \Test\DTOs\SectionDTO::class,
        'test.create'   => \Test\DTOs\Test\BaseTestDTO::class,
        'test.update'   => \Test\DTOs\Test\BaseTestDTO::class,
        'question.source.create'   => \Test\DTOs\Question\SourceDTO::class,
        'question.source.update'   => \Test\DTOs\Question\SourceDTO::class,
        'question.source.delete'   => \Test\DTOs\Question\SourceDTO::class,
        'exam.create' => \Test\DTOs\Exam\ExamDTO::class,
        'exam.update' => \Test\DTOs\Exam\ExamDTO::class,
        'exam.updateTest' => \Test\DTOs\Exam\EditTestOfExamDTO::class,
        'exam.exams'    =>   \Test\DTOs\Exam\FilterExamDTO::class,
        'exam.addResult'    =>   \Test\DTOs\ExamResult\ExamResultDTO::class,
        'test.viewSampleExam' => \Test\DTOs\Test\BaseTestDTO::class,
        'exam.updateAnswer' => \Test\DTOs\ExamResult\UserAnswerDTO::class,
        'exam.updateQuestionMark' => \Test\DTOs\ExamResult\UpdateQuestionMarkDTO::class,
        'question.type.create'  => \Test\DTOs\Question\TypeDTO::class,
        'questions.create'  => \Test\DTOs\Question\QuestionDTO::class,
        'questions.update'  => \Test\DTOs\Question\QuestionDTO::class,
        'odmauth.assignUserPermission'  => \ODMAuth\DTOs\AssignUserPermissionDTO::class,
    ]
];