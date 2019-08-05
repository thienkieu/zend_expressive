<?php

declare(strict_types=1);

namespace Test\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;
use Zend\Diactoros\Response\JsonResponse;
use Infrastructure\Validator\ValidatorAdapterInterface;
use Infrastructure\Validator\RequireField;

class CreateTestWithoutSectionValidatorAdapter implements ValidatorAdapterInterface
{
    protected $container;

    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }
    public function isHandleValid($routerName, $request) : bool
    {
        if ($routerName === \Config\AppRouterName::CreateTest) {
            return true;
        }

        return false;
    }

    protected function showErrorMessage($validators, & $responseMessage) {
        $error = [];
        foreach ($validators as $validator) {
            $messages = $validator->getMessages();
            foreach ($messages as $key => $message) {
                $error[$key][] = $message;
            }
        }
        
        $responseMessage = new JsonResponse($error);
        return empty($error);
    }

    protected function getRandomQuestion($test) {
        $ret = [];
        if (property_exists($test, 'sections') ) {
            $sections = $test->sections;
            if (is_array($sections)) {
                foreach ($sections as  $section) {
                    if (property_exists($section, 'questions')) {
                        foreach($section->questions as $question) {
                            if ($question->generateFrom === \Config\AppConstant::Random){
                                $ret[] =  $question;
                            }
                        }                      
                    }
                }
            }
        }
        return $ret;        
    }

    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool
    {
        $validData = $request->getParsedBody();
        $messageFormat = 'Field \'%field%\' can not empty';
        $messageKey = '%field%';

        $testValidator = new RequireField(
            $this->container->get(\Config\AppConstant::Translator),
            $messageKey,
            $messageFormat,
            [
                'title' => 'title', 
                'sections' => 'sections',
            ]
        );

        $sectionTestValidator = new SectionTestValidator(
            $this->container->get(\Config\AppConstant::Translator),
            $messageKey,
            $messageFormat
        );

        $validatorChain = new ValidatorChain();
        $validatorChain->attach($testValidator);
        $validatorChain->attach($sectionTestValidator);
        $validatorChain->isValid($validData);        

        $validators = [];
        $validators[] = $validatorChain;

        $randomQuestions = $this->getRandomQuestion($validData);
        
        foreach ($randomQuestions as $value) {
            $v = new RandomQuestionValidator(
                $this->container->get(\Config\AppConstant::Translator),
                $messageKey,
                $messageFormat
            );

            $v->isValid($value);
            $validators[] = $v;            
        }
                
        return $this->showErrorMessage($validators, $messageResponse);
    }

    
}
