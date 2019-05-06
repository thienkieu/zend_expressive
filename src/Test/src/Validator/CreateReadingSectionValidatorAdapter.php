<?php

declare(strict_types=1);

namespace Test\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Validator\StringLength;
use Zend\Validator\InArray;
use Zend\Validator\ValidatorChain;
use Zend\Diactoros\Response\JsonResponse;
use Infrastructure\Validator\ValidatorAdapterInterface;
use Infrastructure\CommonFunction;
use Config\AppConstant;

use Test\Enum\DTOName;


class CreateReadingSectionValidatorAdapter implements ValidatorAdapterInterface
{
    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function isHandle($request) : bool
    {
        $name = $request->getAttribute(AppConstant::RequestDTOName);
        $body = $request->getParsedBody();
           
        $type = $body->type;
        if ($name === DTOName::Section && $type === DTOName::Reading ) {
            return true;
        }
        
        return false;
    }

    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool
    {   
        $bodyRequest = $request->getParsedBody();
        
        $translator = $this->container->get(AppConstant::Translator);
        $errors = [];
        $isError = false;

        if (count($bodyRequest->questions) < 1) {
            $isError = true;
            $errors = [
                'questions' => [
                    $translator->translate('Must have at least one question.')
                ]
            ];            
        }
        
        if (!isset($bodyRequest->content) || strlen($bodyRequest->content) < 1) {
            $isError = true;
            $errors = [
                'content' => [
                    $translator->translate('Section content can not empty.')
                ]
            ];            
        }

        $messageResponse = CommonFunction::buildResponseFormat(!$isError, $errors, null);
        return !$isError;
    }
}
