<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class WritingQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();        
        return $ret;
    }
}