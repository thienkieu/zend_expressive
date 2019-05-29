<?php
namespace Test\Enum;

abstract class DTOName
{
    const Reading = 'reading';
    const Writing = 'writing';
    const Listening = 'listening';
    const Section = \Test\DTOs\SectionDTO::class;
    const Test = \Test\DTOs\Test\BaseTestDTO::class;
}
