<?php
namespace Test\Enum;

abstract class DTOName
{
    const Reading = 'Reading';
    const Writing = 'Writing';
    const Listening = 'Listening';
    const Section = \Test\DTOs\Test\SectionDTO::class;
    const Test = \Test\DTOs\Test\BaseTestDTO::class;
    const QuestionDTO = \Test\DTOs\Question\QuestionDTO::class;
    const SourceDTO = \Test\DTOs\Question\SourceDTO::class;
}
