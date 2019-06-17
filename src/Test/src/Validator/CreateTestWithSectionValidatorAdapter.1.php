<?php

declare(strict_types=1);

namespace Test\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;
use Zend\Diactoros\Response\JsonResponse;
use Infrastructure\Validator\ValidatorAdapterInterface;

class CreateTestWithSectionValidatorAdapter implements ValidatorAdapterInterface
{
    public function isHandleValid($routerName, $request) : bool
    {
        if ($routerName === \Config\AppRouterName::CreateTest) {
            return true;
        }

        return false;
    }

    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new StringLength(['min' => 6, 'max' => 12]));
        
        if ($validatorChain->isValid($request->getAttribute('name'))) {
            return true;
        }

        $error = [
            'name' => []
        ];

        foreach ($validatorChain->getMessages() as $message) {
            $error['name'][] = $message;
        }
        $messageResponse = new JsonResponse($error);
        
        return false;
    }
}
