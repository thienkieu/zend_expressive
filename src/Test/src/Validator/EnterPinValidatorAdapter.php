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

class EnterPinValidatorAdapter implements ValidatorAdapterInterface
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
        if ($routerName === \Config\AppRouterName::EnterPin) {
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
        
        $isError = count($error) ? true : false;

        $responseMessage = \Infrastructure\CommonFunction::buildResponseFormat(!$isError, $error);
        return $isError;
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
                'pin' => 'pin',
            ]
        );

        $testValidator->isValid($validData);        
        $validators[] = $testValidator;

        return !$this->showErrorMessage($validators, $messageResponse);
    }

    
}
