<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class WritingQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();   
        $ret->type = $this->getType();
        $ret->subType = $this->getSubType();   
        return $ret;
    }
}