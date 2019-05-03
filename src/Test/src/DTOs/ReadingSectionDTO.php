<?php

declare(strict_types=1);

namespace Test\DTOs;

class ReadingSectionDTO extends SectionDTO implements \JsonSerializable
{
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();
        $ret->questions = $this->getQuestions();
        return $ret;
    }
}
