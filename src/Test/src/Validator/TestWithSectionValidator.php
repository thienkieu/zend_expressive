<?php 

declare(strict_types=1);

namespace Infrastructure\Validator;

use Zend\Validator\AbstractValidator;

class TestWithSectionValidator extends AbstractValidator {
    protected $options = [];
    
    /**
     * Returns true if and only if the string length of $value is at least the min option and
     * no greater than the max option (when the max option is not null).
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($objectValue)
    {
        $requireField = new RequireField([
            'title' => 'title',
            'sectionsName'=>'sections=>name',
            'sectionsDescription'=>'sections=>description',
            'questions'=>'questions',
        ]);

        $requireField->setMessageFormat('Field \'%field%\' can not empty');
        $requireField->setMessageKey('%field%');
        $requireField->setTranslateObject($this->container->get(\Config\AppConstant::Translator));
        
    }

}