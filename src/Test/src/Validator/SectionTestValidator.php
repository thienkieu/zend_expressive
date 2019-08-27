<?php 

declare(strict_types=1);

namespace Test\Validator;

use Infrastructure\Validator\RequireField;

use Zend\Validator\AbstractValidator;

class SectionTestValidator extends RequireField {
    protected $options = [
        'section.name'=>'sections=>name',
        'section.questions'=>'sections=>questions',
    ];
}