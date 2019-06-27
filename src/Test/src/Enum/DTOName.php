<?php
namespace Test\Enum;

abstract class DTOName
{
    const Reading = 'Reading';
    const Writing = 'Writing';
    const Listening = 'Listening';
    const Section = \Test\DTOs\Test\SectionDTO::class;
    const Test = \Test\DTOs\Test\BaseTestDTO::class;
    const Exam = \Test\DTOs\Exam\ExamDTO::class;
    const Candidate = \Test\DTOs\Exam\CandidateDTO::class;
    const QuestionDTO = \Test\DTOs\Question\QuestionDTO::class;
    const SourceDTO = \Test\DTOs\Question\SourceDTO::class;
    const PinDTO = \Test\DTOs\Exam\PinDTO::class;
    const PinInfoDTO = \Test\DTOs\Exam\PinInfoDTO::class;
    const UserAnswerDTO = \Test\DTOs\ExamResult\UserAnswerDTO::class;
    const UpdateQuestionMark = \Test\DTOs\ExamResult\UpdateQuestionMarkDTO::class;
}
