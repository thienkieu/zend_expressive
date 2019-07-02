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
use Infrastructure\Validator\ObjectField;

class CreateExamWithSectionValidatorAdapter implements ValidatorAdapterInterface
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
        if ($routerName === \Config\AppRouterName::CreateExam) {
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
                        $ret = array_merge($ret, $section->questions) ;                        
                    }
                }
            }
        }
        return $ret;        
    }

    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool
    {
        $validData = $request->getParsedBody();
        
        $validator = new StringLength(['min' => 8, 'max' => 12]);
        $sectionTestValidator = new SectionTestValidator(
            $this->container->get(\Config\AppConstant::Translator)
        );
        $objectFieldValidator = new ObjectField(
            $this->container->get(\Config\AppConstant::Translator),
            false,
            [
                'title' => [
                    new \Zend\Validator\NotEmpty(),
                ],
                'test.sections.name1' => [
                    new \Zend\Validator\NotEmpty(),
                ]
            ]
        );

        $testValidator = new RequireField(
            $this->container->get(\Config\AppConstant::Translator),
            [
               // 'sections=>sections' => 'test=>sections',
            ]
        );


        $validatorChain = new ValidatorChain();
        $validatorChain->attach($objectFieldValidator);
        $validatorChain->attach($testValidator);
        $validatorChain->isValid($validData);        

        $validators = [];
        $validators[] = $validatorChain;

        $randomQuestions = $this->getRandomQuestion($validData);
        foreach ($randomQuestions as $value) {
            $v = new RandomQuestionValidator(
                $this->container->get(\Config\AppConstant::Translator)
            );

            $v->isValid($value);
            $validators[] = $v;
        }
                
        return $this->showErrorMessage($validators, $messageResponse);
    }

    
}
