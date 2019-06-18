<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class ReadingQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->content = $this->getContent();
        $ret->type = $this->getType();
        $ret->subType = $this->getSubType();
        $ret->subQuestions = $this->getSubQuestions();
        return $ret;
    }
}
