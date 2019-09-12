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


class CreateQuestionValidatorAdapter implements ValidatorAdapterInterface
{
    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function isHandleValid($routerName, $request) : bool
    {
        $body = $request->getParsedBody();
        
        $type = isset($body->type) ? $body->type: '';
        if (($routerName === \Config\AppRouterName::CreateQuestion || $routerName === \Config\AppRouterName::UpdateQuestion)
             && 
            ($type === DTOName::Reading || $type === DTOName::Listening )
        ) {
            return true;
        }
        
        return false;
    }

    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool
    {   
        $bodyRequest = $request->getParsedBody();
        $type = isset($bodyRequest->bodyRequest) ? $body->type: '';
        
        $translator = $this->container->get(AppConstant::Translator);
        $errors = [];
        $isError = false;

        if (count($bodyRequest->subQuestions) < 1) {
            $isError = true;
            $errors = [            
                $translator->translate('Must have at least one sub question.')  
            ];            
        }

        $subQuestions = $bodyRequest->subQuestions;
        foreach($subQuestions as $subQuestion) {
            $answers = $subQuestion->answers;
            if (count($answers) < 1) {
                $isError = true;
                $errors = [
                    $translator->translate('Must have at least one answer for question.')
                ]; 
                break;
            }

            $isCorrectAnswer = false;
            foreach($answers as $answer) {
                if ($answer->isCorrect == true) {
                    $isCorrectAnswer = true;
                }
            }

            if (!$isCorrectAnswer) {
                $isError = true;
                $errors = [
                    $translator->translate('Must have at least one correct answer for question.')
                ]; 
                break;
            }
        }
        
        if (empty($bodyRequest->content) && $type === DTOName::Reading ) {
            $isError = true;
            $errors = [
                $translator->translate('Section content can not empty.')
            ];            
        }

        $messageResponse = CommonFunction::buildResponseFormat(!$isError, $errors, null);
        return !$isError;
    }
}
