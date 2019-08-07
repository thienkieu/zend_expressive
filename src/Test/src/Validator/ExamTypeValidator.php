<?php 

declare(strict_types=1);

namespace Test\Validator;

use Zend\Validator\AbstractValidator;

class ExamTypeValidator extends AbstractValidator {
    protected $message = 'Exam type is not valid';

    /**
     * Class constructor.
     */
    public function __construct($translate, $options = [])
    {   
        parent::__construct($options);
        $this->translateObject = $translate;  
    }

    /**
     * Returns true if and only if the string length of $value is at least the min option and
     * no greater than the max option (when the max option is not null).
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($objectValue)
    {
        return \App\Enums\ExamType::isValid($objectValue);
    }

    protected function error($key, $value = null) {
        $message = $this->translateObject->translate($this->message) ;;
        $this->abstractOptions['messages'][] = $message;
    }
}