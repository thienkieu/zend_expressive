<?php

declare(strict_types=1);

namespace App\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;
use Zend\Diactoros\Response\JsonResponse;
use Infrastructure\Validator\ValidatorAdapterInterface;

use Test\Enum\DTOName;

class VerifyValidatorAdapter implements ValidatorAdapterInterface
{
    
    public function isHandle($request) : bool
    {
        $name = $request->getAttribute('dto_name');
        if ($name === DTOName::Listening) {
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
