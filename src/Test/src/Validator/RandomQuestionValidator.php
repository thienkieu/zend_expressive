<?php 

declare(strict_types=1);

namespace Test\Validator;

use Infrastructure\Validator\RequireField;

use Zend\Validator\AbstractValidator;

class RandomQuestionValidator extends RequireField {
    protected $options = [
        'questions.type'=>'type',
        'questions.isDifferentSource'=>'isDifferentSource',
        'questions.numberQuestion'=>'numberSubQuestion',
        'question.subType'=>'subType',
        'generateFrom'=>'generateFrom',
    ];
}